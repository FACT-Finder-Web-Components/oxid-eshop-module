<?php

declare(strict_types=1);

use OxidEsales\Eshop\Core\Model\BaseModel;

function smarty_modifier_record_id(BaseModel $article): string
{
    return (string) $article->getFieldData('oxartnum');
}
