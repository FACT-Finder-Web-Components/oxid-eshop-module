<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Config;

class FieldRolesMapper
{
    public function map(array $fieldRoles): array
    {
        $getRole = $this->getOrEmptyString($fieldRoles);

        return [
            'brand'                 => $getRole('brand'),
            'deeplink'              => $getRole('deeplink'),
            'description'           => $getRole('description'),
            'displayProductNumber'  => $getRole('productNumber'),
            'ean'                   => $getRole('ean'),
            'imageUrl'              => $getRole('imageUrl'),
            'masterId'              => $getRole('masterId'),
            'price'                 => $getRole('price'),
            'productName'           => $getRole('productName'),
            'productNumber'         => $getRole('productNumber'),
        ];
    }

    private function getOrEmptyString(array $fieldRoles): callable
    {
        return fn (string $key) => $fieldRoles[$key] ?? '';
    }
}
