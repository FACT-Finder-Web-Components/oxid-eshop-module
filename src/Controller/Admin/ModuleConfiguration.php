<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Controller\Admin;

use OxidEsales\Eshop\Core\Registry;

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
            $this->addTplParam('shopLanguages', Registry::getLang()->getActiveShopLanguageIds());
            $this->addTplParam('localizedFields', array_reduce($this->localizedFields, [$this, 'toArray'], []));
        }
        return $template;
    }

    public function saveConfVars()
    {
        if ($this->isFactFinder()) {
            $_POST['confaarrs'] = array_reduce($this->localizedFields, [$this, 'fromArray'], $_POST['confaarrs'] ?? []);
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
}
