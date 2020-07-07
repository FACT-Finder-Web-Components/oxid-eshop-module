<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Config;

use Omikron\FactFinder\Oxid\Contract\Config\ParametersSourceInterface;
use OxidEsales\Eshop\Application\Controller\FrontendController;
use OxidEsales\Eshop\Application\Model\Category;

class Communication implements ParametersSourceInterface
{
    /** @var FrontendController */
    protected $view;

    public function __construct(FrontendController $view)
    {
        $this->view = $view;
    }

    public function getParameters(): array
    {
        $category = $this->view->getActiveCategory();
        return [
            'url'                         => $this->getConfig('ffServerUrl'),
            'version'                     => $this->getConfig('ffApiVersion'),
            'api'                         => $this->getConfig('ffApiVersion') ? 'v3' : '',
            'channel'                     => $this->getConfig('ffChannel'),
            'use-url-parameter'           => $this->getConfig('ffUseUrlParams') ? 'true' : 'false',
            'disable-single-hit-redirect' => 'true',
            'currency-code'               => $this->view->getActCurrency()->name,
            'currency-country-code'       => $this->getLocale($this->view->getActiveLangAbbr()),
            'add-params'                  => $this->useForCategories() ? $this->getCategoryPath($category) : '',
            'search-immediate'            => $this->isSearch() || $this->useForCategories() ? 'true' : 'false',
            'keep-url-params'             => 'true',
            'only-search-params'          => 'true',
            'use-browser-history'         => 'true',
        ];
    }

    protected function getLocale(string $abbr): string
    {
        $locales = ['de' => 'de-DE', 'en' => 'en-US'];
        return $locales[$abbr] ?? $locales['en'];
    }

    protected function getCategoryPath(Category $category, string $param = 'CategoryPath'): string
    {
        $categories = [$category];
        while ($parent = $category->getParentCategory()) {
            $categories[] = $parent;
            $category     = $parent;
        }

        if ($this->getConfig('ffApiVersion') === 'NG') {
            $path = implode('/', array_map(function (Category $category) {
                return rawurlencode($category->getTitle());
            }, array_reverse($categories)));

            return sprintf('navigation=true,filter=%s:%s', urlencode($param), urlencode($path));
        }

        $path  = 'ROOT';
        $value = ['navigation=true'];
        foreach (array_reverse($categories) as $category) {
            $value[] = sprintf("filter{$param}%s=%s", $path, rawurlencode($category->getTitle()));
            $path    .= '/' . rawurlencode($category->getTitle());
        }
        return implode(',', $value);
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
}
