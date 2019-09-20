<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Export;

abstract class AbstractEntity
{
    /** @var array */
    protected $data = [];

    public function __construct(array $data = [])
    {
        $this->data = $data;
        //cast all values to string in case of NULL value is fetched from db
        array_walk($data, function ($value, $key) {
            $this->data[$key] = (string) $value;
        });
    }

    public function setData(string $key, string $value = '')
    {
        $this->data[$key] = $value;

        return $this;
    }

    public function __call(string $method, $_): string
    {
        if (substr($method, 0, 3) == 'get') {
            $key = substr($method, 3);
            return isset($this->data[$key]) ? $this->data[$key] : '';
        }

        return '';
    }

    /**
     * Convert entity data to associative array
     *
     * @return array
     */
    abstract public function toArray(): array;
}
