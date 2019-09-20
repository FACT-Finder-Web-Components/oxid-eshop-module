<?php

use OxidEsales\Eshop\Core\Model\BaseModel;

function smarty_modifier_record_id(BaseModel $article, string $table = 'oxarticles'): string
{
    return (string) $article->{$table . '__oxartnum'};
}
