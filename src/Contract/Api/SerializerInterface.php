<?php

namespace Omikron\FactFinder\Oxid\Contract\Api;

interface SerializerInterface
{
    /**
     * @param array $data
     *
     * @return string
     */
    public function serialize($data): string;

    /**
     * @param string $string
     *
     * @return array
     */
    public function unserialize($string): array;
}
