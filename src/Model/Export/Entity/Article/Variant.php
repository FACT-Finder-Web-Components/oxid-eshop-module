<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Export\Entity\Article;

use Omikron\FactFinder\Oxid\Contract\Export\ExportEntityInterface;
use Omikron\FactFinder\Oxid\Model\Export\AbstractEntity;

class Variant extends AbstractEntity implements ExportEntityInterface
{
    public function toArray(): array
    {
        $this->setData('Attributes', $this->getAttributes() != '' ? ('|' . $this->getAttributes() . '|') : '');
        return $this->data;
    }
}
