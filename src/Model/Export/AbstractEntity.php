<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Export;

abstract class AbstractEntity
{
    /** @var array */
    protected $data = [];

    public function __construct(array $data = [])
    {
        // Cast all values to string in case NULL is fetched from the DB
        $this->data = array_map('strval', $data);
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
            return $this->data[$key] ?? '';
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
