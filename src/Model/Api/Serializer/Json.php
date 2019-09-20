<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Api\Serializer;

use Omikron\FactFinder\Oxid\Contract\Api\SerializerInterface;

class Json implements SerializerInterface
{
    /**
     * @param mixed $data
     *
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    public function serialize($data): string
    {
        $result = json_encode($data);
        if (false === $result) {
            throw new \InvalidArgumentException('Unable to serialize value.');
        }

        return $result;
    }

    /**
     * @param string $string
     *
     * @return array
     *
     * @throws \InvalidArgumentException
     */
    public function unserialize($string): array
    {
        $result = json_decode((string) $string, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException('Unable to unserialize value.');
        }

        return $result;
    }
}
