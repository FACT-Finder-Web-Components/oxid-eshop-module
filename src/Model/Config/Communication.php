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

    /**
     * @SuppressWarnings("PMD.CyclomaticComplexity")
     *
     * @return array
     */
    public function getParameters(): array
    {
        $category = $this->view->getActiveCategory();
        $params   = [
            'url'                   => $this->getServerUrl(),
            'version'               => $this->getConfig('ffVersion'),
            'api'                   => $this->getConfig('ffVersion') ? $this->getApiVersion() : '',
            'channel'               => $this->getChannel($this->view->getActiveLangAbbr()),
            'user-id'               => $this->getUserId(),
            'use-url-parameters'    => $this->getConfig('ffUseUrlParams') ? 'true' : 'false',
            'currency-code'         => $this->view->getActCurrency()->name,
            'currency-fields'       => $this->getAdditionalCurrencyFields(),
            'currency-country-code' => $this->getLocale($this->view->getActiveLangAbbr()),
            'search-immediate'      => $this->isSearch() || $this->useForCategories() || $this->useProxy() ? 'true' : 'false',
            'keep-url-params'       => 'true',
            'only-search-params'    => 'true',
            'use-browser-history'   => 'true',
            'category-page'         => $this->getConfig('ffVersion') === 'ng' && $this->useForCategories() ? $this->getCategoryPath($category) : null,
            'add-params'            => $this->getConfig('ffVersion') !== 'ng' && $this->useForCategories() ? $this->getCategoryPath($category) : '',
        ];

        return array_filter($this->mergeParameters($params, $this->getAdditionalParameters()));
    }

    protected function getUserId(): string
    {
        $session = Registry::getSession();

        if (!$session->getUser()) {
            return '';
        }

        $userId = $session->getUser()->getFieldData('oxcustnr');

        return $this->getConfig('ffAnonymizeUserId') ? md5($userId) : $userId;
    }

    protected function getLocale(string $abbr): string
    {
        $locales = ['de' => 'de-DE', 'en' => 'en-US'];
        return $locales[$abbr] ?? $locales['en'];
    }

    protected function getServerUrl(): string
    {
        return (string) $this->getConfig('ffUseProxy') ? 'index.php' : $this->getConfig('ffServerUrl');
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

        return $this->getConfig('ffVersion') === 'ng'
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

    protected function getApiVersion(): string
    {
        return (string) $this->getConfig('ffApiVersion') ?? 'v4';
    }

    private function ngPath(array $categories, string $param): string
    {
        $categoryPath = array_map(function ($category) {
            return (string) $this->encodeCategoryName(trim($category));
        }, array_reverse($categories));

        return sprintf('filter=%s', urlencode($param . ':' . implode('/', $categoryPath)));
    }

    private function standardPath(array $categories, string $param): string
    {
        $path  = 'ROOT';
        $value = ['navigation=true'];
        foreach (array_reverse($categories) as $category) {
            $value[] = sprintf("filter{$param}%s=%s", $path, urlencode(trim($category)));
            $path .= urlencode('/' . $this->encodeCategoryName(trim($category)));
        }

        return implode(',', $value);
    }

    private function encodeCategoryName(string $path): string
    {
        //important! do not modify this code
        return preg_replace(
            '/\+/',
            '%2B',
            preg_replace('/\//', '%2F', preg_replace('/%/', '%25', $path))
        );
    }

    private function useProxy(): bool
    {
        return (bool) $this->getConfig('ffUseProxy');
    }
}
