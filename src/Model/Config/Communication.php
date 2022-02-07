<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Config;

use Omikron\FactFinder\Oxid\Contract\Config\ParametersSourceInterface;
use Omikron\FactFinder\Oxid\Export\Filter\TextFilter;
use OxidEsales\Eshop\Application\Controller\FrontendController;
use OxidEsales\Eshop\Application\Model\Category;
use OxidEsales\Eshop\Core\Registry;
use RuntimeException;

class Communication implements ParametersSourceInterface
{
    /** @var FrontendController */
    protected $view;

    /** @var string[] */
    protected $mergeableParams = ['add-params', 'add-tracking-params', 'keep-url-params', 'parameter-whitelist'];

    /** @var TextFilter */
    private $filter;

    public function __construct(FrontendController $view)
    {
        $this->view   = $view;
        $this->filter = oxNew(TextFilter::class);
    }

    public function getParameters(): array
    {
        $category = $this->view->getActiveCategory();
        $session  = Registry::getSession();

        $params = [
            'url'                         => $this->getConfig('ffServerUrl'),
            'version'                     => $this->getConfig('ffApiVersion'),
            'api'                         => $this->getConfig('ffApiVersion') ? 'v4' : '',
            'channel'                     => $this->getChannel($this->view->getActiveLangAbbr()),
            'user-id'                     => $session->getUser() ? $session->getUser()->getFieldData('oxcustnr') : '',
            'use-url-parameters'          => $this->getConfig('ffUseUrlParams') ? 'true' : 'false',
            'currency-code'               => $this->view->getActCurrency()->name,
            'currency-fields'             => $this->getAdditionalCurrencyFields(),
            'currency-country-code'       => $this->getLocale($this->view->getActiveLangAbbr()),
            'search-immediate'            => $this->isSearch() || $this->useForCategories() ? 'true' : 'false',
            'keep-url-params'             => 'cl',
            'only-search-params'          => 'true',
            'use-browser-history'         => 'true',
            'category-page'               => $this->getConfig('ffApiVersion') === 'ng' ? $this->fillCategoryPath($category) : null,
            'add-params'                  => $this->getConfig('ffApiVersion') === 'ng' ? $this->fillAddParamsForNg() : $this->fillAddParamsStandard($category),
        ];

        return array_filter($this->mergeParameters($params, $this->getAdditionalParameters()));
    }

    protected function getLocale(string $abbr): string
    {
        $locales = ['de' => 'de-DE', 'en' => 'en-US'];
        return $locales[$abbr] ?? $locales['en'];
    }

    protected function getCategoryPath(Category $category, string $param = 'CategoryPath'): string
    {
        $categories = [rawurlencode($this->filter->filterValue($category->getTitle()))];
        while ($parent = $category->getParentCategory()) {
            $categories[] = rawurlencode($this->filter->filterValue($parent->getTitle()));
            $category     = $parent;
        }

        return $this->getConfig('ffApiVersion') === 'ng' ? $this->encodeNgCategoryPath($categories, $param) : $this->encodeStandardCategoryPath($categories, $param);
    }

    protected function getConfig(string $name)
    {
        return $this->view->getConfig()->getConfigParam($name);
    }

    protected function isSearch(): bool
    {
        return $this->view->getActionClassName() === 'search_result';
    }

    protected function useForCategories(): bool
    {
        return $this->getConfig('ffUseForCategories') && $this->view->getActionClassName() === 'alist';
    }

    protected function getAdditionalCurrencyFields(): string
    {
        return '';
    }

    protected function getAdditionalParameters(): array
    {
        return (array) $this->getConfig('ffAddSearchParams');
    }

    protected function mergeParameters(array $baseParams, array $additionalParams): array
    {
        return array_reduce($this->mergeableParams, function (array $result, string $param) use ($additionalParams) {
            return [$param => implode(',', array_column([$additionalParams, $result], $param))] + $result;
        }, $baseParams);
    }

    protected function getChannel(string $langAbbr): string
    {
        $channels = $this->getConfig('ffChannel');

        if (!isset($channels[$langAbbr])) {
            throw new RuntimeException("No channel for used language: $langAbbr");
        }

        return $channels[$langAbbr];
    }

    private function encodeNgCategoryPath(array $categories, string $param): string
    {
        $categoryPath = implode('/', array_reverse($categories));
        $path         = sprintf('%s:%s', $param, $categoryPath);

        return sprintf('filter=%s', $this->urlPlusEncodeCategoryPath($path));
    }

    private function encodeStandardCategoryPath(array $categories, string $param): string
    {
        $categoriesReverse = array_reverse($categories);
        $path              = 'ROOT';
        $value             = ['navigation=true'];
        foreach ($categoriesReverse as $key => $category) {
            $filterValue = sprintf("filter{$param}%s=%s", $path, $this->urlPlusEncodeCategoryPath($category));
            if ($key >= 1) {
                $path .= $this->urlPlusEncodeCategoryPath(urlencode('/') . $categoriesReverse[$key - 1]);
                $filterValue = sprintf("filter{$param}%s=%s", $path, $this->urlPlusEncodeCategoryPath($category));
            }
            $value[] = $filterValue;
        }

        return implode(',', $value);
    }

    private function fillCategoryPath(?Category $category): ?string
    {
        return $this->useForCategories() ? $this->getCategoryPath($category) : null;
    }

    private function fillAddParamsForNg(): ?string
    {
        return $this->useForCategories() ? null : 'cl=search_result';
    }

    private function fillAddParamsStandard(?Category $category): string
    {
        return $this->useForCategories() ? $this->getCategoryPath($category) : 'cl=search_result';
    }

    private function urlPlusEncodeCategoryPath(string $path): string
    {
        return str_replace('%20', '+', $path);
    }
}
