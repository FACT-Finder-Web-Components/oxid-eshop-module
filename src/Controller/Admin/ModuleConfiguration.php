<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Controller\Admin;

use Omikron\FactFinder\Oxid\Model\Config\Export as ExportConfig;
use OxidEsales\Eshop\Application\Model\Attribute;
use OxidEsales\Eshop\Application\Model\AttributeList;
use OxidEsales\Eshop\Core\Registry;

/**
 * Module Configuration.
 *
 * @mixin \OxidEsales\Eshop\Application\Controller\Admin\ModuleConfiguration
 */
class ModuleConfiguration extends ModuleConfiguration_parent
{
    /** @var string[] */
    private $localizedFields = ['ffChannel'];

    public function render()
    {
        $template = parent::render();
        if ($this->isFactFinder()) {
            $allAttributes = $this->getAvailableAttributes();

            $this->addTplParam('shopLanguages', Registry::getLang()->getActiveShopLanguageIds());
            $this->addTplParam('localizedFields', array_reduce($this->localizedFields, function (array $result, string $field): array {
                return [$field => $this->_aarrayToMultiline($result[$field] ?? [])] + $result;
            }, []));
            $this->addTplParam('availableAttributes', $allAttributes);
            $this->addTplParam('selectedAttributes', $this->getSelectedAttributes($allAttributes));
        }
        return $template;
    }

    public function saveConfVars()
    {
        if ($this->isFactFinder()) {
            $_POST['confaarrs'] = array_reduce($this->localizedFields, function (array $result, string $field): array {
                $value = html_entity_decode($this->getViewDataElement('confaarrs')[$field] ?? '');
                return $result + [$field => $this->_multilineToAarray($value)];
            }, $_POST['confaarrs'] ?? []);

            $_POST['confaarrs']['ffExportAttributes'] = $this->_aarrayToMultiline(
                $this->flatMap($this->prepareAttributes(), $_POST['confaarrs']['ffExportAttributes'] ?? [])
            );
        }
        return parent::saveConfVars();
    }

    protected function isFactFinder(): bool
    {
        return Registry::getRequest()->getRequestEscapedParameter('oxid') === 'ffwebcomponents';
    }

    private function getAvailableAttributes(): array
    {
        $attributeList = oxNew(AttributeList::class)->getList()->getArray();
        return array_reduce($attributeList, function (array $attributes, Attribute $attribute): array {
            return $attributes + [$attribute->getFieldData('oxid') => $attribute->getFieldData('oxtitle')];
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
}
