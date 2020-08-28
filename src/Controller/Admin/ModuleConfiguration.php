<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Controller\Admin;

use OxidEsales\Eshop\Core\Config;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Application\Model\AttributeList;
use OxidEsales\Eshop\Application\Model\Attribute;

/**
 * Module Configuration
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
            $this->addTplParam('localizedFields', array_reduce($this->localizedFields, [$this, 'toArray'], []));
            $this->addTplParam('availableAttributes', $allAttributes);
            $this->addTplParam('selectedAttributes', $this->getSelectedAttributes($allAttributes));
        }
        return $template;
    }

    public function saveConfVars()
    {
        if ($this->isFactFinder()) {
            $_POST['confaarrs'] = array_reduce($this->localizedFields, [$this, 'fromArray'], $_POST['confaarrs'] ?? []);
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

    private function toArray(array $result, string $field): array
    {
        $value = html_entity_decode($this->getViewDataElement('confaarrs')[$field] ?? '');
        return $result + [$field => $this->_multilineToAarray($value)];
    }

    private function fromArray(array $result, string $field): array
    {
        return [$field => $this->_aarrayToMultiline($result[$field] ?? [])] + $result;
    }

    private function getAvailableAttributes(): array
    {
        $attributeList = oxNew(AttributeList::class);
        $attributeList->setBaseObject(oxNew(Attribute::class));

        return array_reduce($attributeList->getList()->getArray(), function (array $attributes, Attribute $attribute): array {
            return $attributes + [$attribute->getFieldData('oxid') => $attribute->getFieldData('oxtitle')];
        }, []);
    }

    private function getSelectedAttributes(array $allAttributes): array
    {
        $selected = $this->getConfig()->getConfigParam('ffExportAttributes');

        return array_map(function (string $attributeId) use ($selected, $allAttributes) : array {
            return [
                'id'    => $attributeId,
                'title' => $allAttributes[$attributeId],
                'multi' => $selected[$attributeId]
            ];
        }, array_keys($selected));
    }

    private function prepareAttributes(): callable
    {
        return function (array $attributeData): array {
            return [$attributeData['id'] => $attributeData['multi']];
        };
    }

    private function flatMap(callable $fn, array $arr): array
    {
        return array_merge([], ...array_map($fn, $arr));
    }
}
