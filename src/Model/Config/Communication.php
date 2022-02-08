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
            'url'                   => $this->getConfig('ffServerUrl'),
            'version'               => $this->getConfig('ffApiVersion'),
            'api'                   => $this->getConfig('ffApiVersion') ? 'v4' : '',
            'channel'               => $this->getChannel($this->view->getActiveLangAbbr()),
            'user-id'               => $session->getUser() ? $session->getUser()->getFieldData('oxcustnr') : '',
            'use-url-parameters'    => $this->getConfig('ffUseUrlParams') ? 'true' : 'false',
            'currency-code'         => $this->view->getActCurrency()->name,
            'currency-fields'       => $this->getAdditionalCurrencyFields(),
            'currency-country-code' => $this->getLocale($this->view->getActiveLangAbbr()),
            'search-immediate'      => $this->isSearch() || $this->useForCategories() ? 'true' : 'false',
            'keep-url-params'       => 'cl',
            'only-search-params'    => 'true',
            'use-browser-history'   => 'true',
            'category-page'         => $this->getConfig('ffApiVersion') === 'ng' && $this->useForCategories() ? $this->getCategoryPath($category) : null,
            'add-params'            => $this->getConfig('ffApiVersion') === 'ng'
                ? ($this->useForCategories() ? null : 'cl=search_result')
                : ($this->useForCategories() ? $this->getCategoryPath($category) : 'cl=search_result'),
        ];

        return array_filter($this->mergeParameters($params, $this->getAdditionalParameters()));
    }

    protected function getLocale(string $abbr): string
    {
        $locales = ['de' => 'de-DE', 'en' => 'en-US'];
        return $locales[$abbr] ?? $locales['en'];
    }

    /**
     * @deprecated will be removed in v5
     */
    protected function getCategoryPath(Category $category, string $param = 'CategoryPath'): string
    {
        $categories = [$this->filter->filterValue($category->getTitle())];
        while ($parent = $category->getParentCategory()) {
            $categories[] = $this->filter->filterValue($parent->getTitle());
            $category     = $parent;
        }

        return $this->getConfig('ffApiVersion') === 'ng'
            ? $this->ngPath($categories, $param)
            : $this->standardPath($categories, $param);
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

    private function ngPath(array $categories, string $param): string
    {
        $categoryPath = implode(urlencode('/'), array_map(function ($category) {
            return $this->plusDoubleEncode($category);
        }, array_reverse($categories)));

        return sprintf('filter=%s%s', urlencode("$param:"), $categoryPath);
    }

    private function standardPath(array $categories, string $param): string
    {
        $categoriesReverse = array_reverse($categories);
        $path              = 'ROOT';
        $value             = ['navigation=true'];
        foreach ($categoriesReverse as $key => $category) {
            $path .= $key === 0 ? null : urlencode('/') . $this->plusDoubleEncode($categoriesReverse[$key - 1]);
            $parameterKey = urlencode($param) . $path;
            $value[]      = sprintf('filter%s=%s', $parameterKey, urlencode($category));
        }

        return implode(',', $value);
    }

    private function plusDoubleEncode(string $path): string
    {
        return urlencode(str_replace('+', ' ', urlencode($path)));
    }
}
