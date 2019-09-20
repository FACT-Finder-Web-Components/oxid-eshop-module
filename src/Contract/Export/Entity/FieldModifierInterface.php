<?php

namespace Omikron\FactFinder\Oxid\Contract\Export\Entity;

use Omikron\FactFinder\Oxid\Model\Export\AbstractEntity;

interface FieldModifierInterface
{
    public function getName(): string;

    public function getValue(AbstractEntity $entity): string;
}
