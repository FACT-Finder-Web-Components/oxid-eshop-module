<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Config;

use Omikron\FactFinder\Oxid\Contract\Config\ParametersSourceInterface;
use Omikron\FactFinder\Oxid\Export\Filter\TextFilter;
use OxidEsales\Eshop\Application\Controller\FrontendController;
use OxidEsales\Eshop\Application\Model\Category;
use OxidEsales\Eshop\Core\Controller\BaseController;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Facade\ModuleSettingServiceInterface;
use RuntimeException;

class Communication implements ParametersSourceInterface
{
    protected array $mergeableParams = ['add-params', 'add-tracking-params', 'keep-url-params', 'parameter-whitelist'];

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
            'version'               => $this->moduleSettingService->getString('ffVersion', 'ffwebcomponents'),
            'api'                   => $this->moduleSettingService->getString('ffVersion', 'ffwebcomponents') ? $this->getApiVersion() : '',
            'channel'               => $this->getChannel($this->view->getActiveLangAbbr()),
            'user-id'               => $this->getUserId(),
            'use-url-parameters'    => $this->moduleSettingService->getBoolean('ffUseUrlParams', 'ffwebcomponents') ? 'true' : 'false',
            'currency-code'         => $this->view->getActCurrency()->name,
            'currency-fields'       => $this->getAdditionalCurrencyFields(),
            'currency-country-code' => $this->getLocale($this->view->getActiveLangAbbr()),
            'search-immediate'      => $this->isSearch() || $this->useForCategories() || $this->useProxy() ? 'true' : 'false',
            'keep-url-params'       => 'true',
            'only-search-params'    => 'true',
            'use-browser-history'   => 'true',
            'category-page'         => (string) $this->moduleSettingService->getString('ffVersion', 'ffwebcomponents') === 'ng' && $this->useForCategories() ? $this->getCategoryPath($category) : null,
            'add-params'            => (string) $this->moduleSettingService->getString('ffVersion', 'ffwebcomponents') !== 'ng' && $this->useForCategories() ? $this->getCategoryPath($category) : '',
            'disable-cache'         => $this->moduleSettingService->getBoolean('ffDisableCache', 'ffwebcomponents') ? 'true' : 'false',
        ];

        return array_filter($this->mergeParameters($params, $this->getAdditionalParameters()));
    }

    public function getTrackingSettings(): array
    {
        return [
            'addToCart' => [
                'count' => (string) $this->moduleSettingService->getString('ffTrackingAddToCartCount', 'ffwebcomponents') ?? 'count_as_one',
            ],
        ];
    }

    public function useSidAsUserId(): bool
    {
        return $this->moduleSettingService->getBoolean('ffSidAsUserId', 'ffwebcomponents') ?? false;
    }

    protected function getUserId(): string
    {
        $session = Registry::getSession();

        if (!$session->getUser()) {
            return '';
        }

        $userId = (string) $session->getUser()->getFieldData('oxcustnr');

        return $this->moduleSettingService->getBoolean('ffAnonymizeUserId', 'ffwebcomponents') ? md5($userId) : $userId;
    }

    protected function getLocale(string $abbr): string
    {
        $locales = ['de' => 'de-DE', 'en' => 'en-US'];

        return $locales[$abbr] ?? $locales['en'];
    }

    protected function getServerUrl(): string
    {
        return (string) $this->moduleSettingService->getBoolean('ffUseProxy', 'ffwebcomponents') ?
            'index.php' :
            (string) $this->moduleSettingService->getString('ffServerUrl', 'ffwebcomponents');
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

        return (string) $this->moduleSettingService->getString('ffVersion', 'ffwebcomponents') === 'ng'
            ? $this->ngPath($categories, $param)
            : $this->standardPath($categories, $param);
    }

    protected function isSearch(): bool
    {
        return $this->view->getActionClassName() === 'search_result';
    }

    protected function useForCategories(): bool
    {
        return $this->moduleSettingService->getBoolean('ffUseForCategories', 'ffwebcomponents') && $this->view->getActionClassName() === 'alist';
    }

    protected function getAdditionalCurrencyFields(): string
    {
        return '';
    }

    protected function getAdditionalParameters(): array
    {
        return (array) $this->moduleSettingService->getCollection('ffAddSearchParams', 'ffwebcomponents');
    }

    protected function mergeParameters(array $baseParams, array $additionalParams): array
    {
        return array_reduce($this->mergeableParams, function (array $result, string $param) use ($additionalParams) {
            return [$param => implode(',', array_column([$additionalParams, $result], $param))] + $result;
        }, $baseParams);
    }

    protected function getChannel(string $langAbbr): string
    {
        $channels = $this->moduleSettingService->getCollection('ffChannel', 'ffwebcomponents');

        if (!isset($channels[$langAbbr])) {
            throw new RuntimeException("No channel for used language: $langAbbr");
        }

        return $channels[$langAbbr];
    }

    protected function getApiVersion(): string
    {
        return (string) $this->moduleSettingService->getString('ffApiVersion', 'ffwebcomponents') ?? 'v4';
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
        return (bool) $this->moduleSettingService->getBoolean('ffUseProxy', 'ffwebcomponents');
    }
}
