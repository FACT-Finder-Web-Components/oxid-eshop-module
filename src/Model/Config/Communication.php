<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Config;

use Omikron\FactFinder\Oxid\Contract\Config\ParametersSourceInterface;
use Omikron\FactFinder\Oxid\Export\Filter\TextFilter;
use OxidEsales\Eshop\Application\Model\Category;
use OxidEsales\Eshop\Core\Controller\BaseController;
use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Facade\ModuleSettingServiceInterface;

class Communication implements ParametersSourceInterface
{
    private TextFilter $filter;

    private ModuleSettingServiceInterface $moduleSettingService;

    public function __construct(protected readonly BaseController $view)
    {
        $this->filter               = oxNew(TextFilter::class);
        $this->moduleSettingService = ContainerFactory::getInstance()
            ->getContainer()
            ->get(ModuleSettingServiceInterface::class);
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
            'version'               => 'ng',
            'api'                   => $this->getApiVersion(),
            'channel'               => $this->getChannel($this->view->getActiveLangAbbr()),
            'currency-code'         => $this->view->getActCurrency()->name,
            'currency-country-code' => $this->getLocale($this->view->getActiveLangAbbr()),
            'search-immediate'      => $this->isSearch() || $this->useForCategories() || $this->useProxy() ? 'true' : 'false',
            'category-page'         => $this->useForCategories() ? $this->getCategoryPath($category) : null,
            'useSsr'                => $this->useSsr(),
        ];

        return $params;
    }

    public function getTrackingSettings(): array
    {
        return [
            'addToCart' => [
                'count' => (string) $this->moduleSettingService->getString('ffTrackingAddToCartCount', 'ffwebcomponents') ?? 'count_selected_amount',
            ],
        ];
    }

    public function useSidAsUserId(): bool
    {
        return $this->moduleSettingService->getBoolean('ffSidAsUserId', 'ffwebcomponents') ?? false;
    }

    protected function getLocale(string $abbr): string
    {
        $locales = ['de' => 'de-DE', 'en' => 'en-US'];

        return $locales[$abbr] ?? $locales['en'];
    }

    protected function getServerUrl(): string
    {
        return (string) $this->moduleSettingService->getBoolean('ffUseProxy', 'ffwebcomponents') ?
            '' :
            (string) $this->moduleSettingService->getString('ffServerUrl', 'ffwebcomponents');
    }

    protected function getCategoryPath(Category $category): string
    {
        $categories = [$this->filter->filterValue($category->getTitle())];

        while ($parent = $category->getParentCategory()) {
            $categories[] = $this->filter->filterValue($parent->getTitle());
            $category     = $parent;
        }

        return implode(',', array_reverse($categories));
    }

    protected function isSearch(): bool
    {
        return $this->view->getActionClassName() === 'search_result';
    }

    protected function useForCategories(): bool
    {
        return $this->moduleSettingService->getBoolean('ffUseForCategories', 'ffwebcomponents') && $this->view->getActionClassName() === 'alist';
    }

    protected function getChannel(string $langAbbr): string
    {
        $channels = $this->moduleSettingService->getCollection('ffChannel', 'ffwebcomponents');

        if (!isset($channels[$langAbbr])) {
            throw new \RuntimeException("No channel for used language: $langAbbr");
        }

        return $channels[$langAbbr];
    }

    protected function getApiVersion(): string
    {
        return 'v5';
    }

    private function useProxy(): bool
    {
        return (bool) $this->moduleSettingService->getBoolean('ffUseProxy', 'ffwebcomponents');
    }

    private function useSsr(): bool
    {
        return (bool) $this->moduleSettingService->getBoolean('ffUseSsr', 'ffwebcomponents');
    }
}
