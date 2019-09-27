<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Config;

use Omikron\FactFinder\Oxid\Contract\Config\ParametersSourceInterface;
use OxidEsales\Eshop\Application\Controller\FrontendController;
use OxidEsales\Eshop\Application\Model\Category;

class Communication implements ParametersSourceInterface
{
    /** @var FrontendController */
    private $view;

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
            'channel'                     => $this->getConfig('ffChannel'),
            'use-url-parameter'           => $this->getConfig('ffUseUrlParams') ? 'true' : 'false',
            'disable-single-hit-redirect' => $this->getConfig('ffDisableSingleHit') ? 'true' : 'false',
            'use-browser-history'         => $this->getConfig('ffUseBrowserCache') ? 'true' : 'false',
            'currency-code'               => $this->view->getActCurrency()->name,
            'currency-country-code'       => $this->getLocale($this->view->getActiveLangAbbr()),
            'add-params'                  => $this->useForCategories() ? $this->getCategoryPath($category) : '',
            'search-immediate'            => $this->isSearch() || $this->useForCategories() ? 'true' : 'false',
        ];
    }

    private function getLocale(string $abbr): string
    {
        $locales = ['de' => 'de-DE', 'en' => 'en-US'];
        return $locales[$abbr] ?? $locales['en'];
    }

    private function getCategoryPath(Category $category, string $param = 'CategoryPath'): string
    {
        $categories = [$category];
        while ($parent = $category->getParentCategory()) {
            $categories[] = $parent;
            $category = $parent;
        }

        $path  = 'ROOT';
        $value = ['navigation=true'];
        foreach (array_reverse($categories) as $category) {
            $value[] = sprintf("filter{$param}%s=%s", $path, urlencode($category->getTitle()));
            $path     .= urlencode('/' . $category->getTitle());
        }
        return implode(',', $value);
    }

    private function getConfig(string $name)
    {
        return $this->view->getConfig()->getConfigParam($name);
    }

    private function isSearch(): bool
    {
        return $this->view->getActionClassName() === 'search_result';
    }

    private function useForCategories(): bool
    {
        return $this->getConfig('ffUseForCategories') && $this->view->getActionClassName() === 'alist';
    }
}
