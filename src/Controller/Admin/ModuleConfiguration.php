<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Controller\Admin;

use Omikron\FactFinder\Communication\Client\ClientBuilder;
use Omikron\FactFinder\Communication\Credentials;
use Omikron\FactFinder\Communication\Resource\AdapterFactory;
use Omikron\FactFinder\Oxid\Model\Config\Export as ExportConfig;
use OxidEsales\Eshop\Application\Model\Attribute;
use OxidEsales\Eshop\Application\Model\AttributeList;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Configuration\Bridge\ModuleSettingBridgeInterface;

/**
 * Module Configuration.
 *
 * @mixin \OxidEsales\Eshop\Application\Controller\Admin\ModuleConfiguration
 */
class ModuleConfiguration extends ModuleConfiguration_parent
{
    /** @var ModuleSettingBridgeInterface */
    protected $moduleSettings;

    /** @var string[] */
    private $localizedFields = ['ffChannel'];

    public function render()
    {
        $template = parent::render();
        if ($this->isFactFinder()) {
            $allAttributes = $this->getAvailableAttributes();

            $this->addTplParam('shopLanguages', Registry::getLang()->getActiveShopLanguageIds());
            $this->addTplParam('localizedFields', array_reduce($this->localizedFields, function (array $result, string $field): array {
                $value = html_entity_decode($this->getViewDataElement('confaarrs')[$field] ?? '');
                return $result + [$field => $this->_multilineToAarray($value)];
            }, []));
            $this->addTplParam('availableAttributes', json_encode($allAttributes, JSON_HEX_QUOT | JSON_HEX_APOS));
            $this->addTplParam('selectedAttributes', json_encode($this->getSelectedAttributes($allAttributes), JSON_HEX_QUOT | JSON_HEX_APOS));
        }
        return $template;
    }

    public function saveConfVars()
    {
        try {
            $this->preparePostData();
            parent::saveConfVars();
            $this->addTplSuccessMessage('Module configuration was saved successfully');
        } catch (\Exception $exception) {
            $this->addTplErrorMessage($exception->getMessage());
        }
    }

    public function updateFieldRoles()
    {
        try {
            $clientBuilder = oxNew(ClientBuilder::class)
                ->withServerUrl($this->getConfigParam('ffServerUrl'))
                ->withCredentials($this->getCredentials());

            $searchAdapter = (new AdapterFactory($clientBuilder, $this->getConfigParam('ffApiVersion')))->getSearchAdapter();
            $response = $searchAdapter->search($this->getConfigParam('ffChannel')[Registry::getLang()->getLanguageAbbr()], '*');
            $fieldRoles = $response['fieldRoles'] ?? $response['searchResult']['fieldRoles'];

            $_POST['confstrs']['ffFieldRoles'] = json_encode($fieldRoles);

            $this->preparePostData();
            parent::saveConfVars();
            $this->addTplSuccessMessage('Field roles was updated successfully');
        } catch (\Exception $exception) {
            $this->addTplErrorMessage($exception->getMessage());
        }
    }

    protected function isFactFinder(): bool
    {
        return Registry::getRequest()->getRequestEscapedParameter('oxid') === 'ffwebcomponents';
    }

    protected function getCredentials(): Credentials
    {
        return new Credentials(...array_map([$this, 'getConfigParam'], ['ffUsername', 'ffPassword', 'ffAuthPrefix', 'ffAuthPostfix']));
    }

    protected function getConfigParam(string $key)
    {
        return Registry::getConfig()->getConfigParam($key);
    }

    private function preparePostData()
    {
        if ($this->isFactFinder()) {
            $_POST['confaarrs'] = array_reduce($this->localizedFields, function (array $result, string $field): array {
                return [$field => $this->_aarrayToMultiline($result[$field] ?? [])] + $result;
            }, $_POST['confaarrs'] ?? []);

            $_POST['confaarrs']['ffExportAttributes'] = $this->_aarrayToMultiline(
                $this->flatMap($this->prepareAttributes(), $_POST['confaarrs']['ffExportAttributes'] ?? [])
            );
        }
    }

    private function getAvailableAttributes(): array
    {
        $attributeList = oxNew(AttributeList::class)->getList()->getArray();
        return array_reduce($attributeList, function (array $attributes, Attribute $attribute): array {
            return $attributes + [$attribute->getFieldData('oxid') => $attribute->oxattribute__oxtitle->rawValue];
        }, []);
    }

    private function getSelectedAttributes(array $allAttributes): array
    {
        $selectedConfig = oxNew(ExportConfig::class)->getConfigValue();
        return array_map(function (string $attributeId) use ($selectedConfig, $allAttributes): array {
            return [
                'id'    => $attributeId,
                'title' => $allAttributes[$attributeId],
                'multi' => $selectedConfig[$attributeId],
            ];
        }, array_keys($selectedConfig));
    }

    private function prepareAttributes(): callable
    {
        return function (array $attributeData): array {
            return [$attributeData['id'] => $attributeData['multi']];
        };
    }

    private function flatMap(callable $fnc, array $arr): array
    {
        return array_merge([], ...array_map($fnc, $arr));
    }

    private function addTplSuccessMessage(string $message): void
    {
        $this->addTplParam('postSubmitMessage', sprintf('<div class="text-success">%s</div>', $message));
    }

    private function addTplErrorMessage(string $message): void
    {
        $this->addTplParam('postSubmitMessage', sprintf('<div class="text-error">%s</div>', $message));
    }
}
