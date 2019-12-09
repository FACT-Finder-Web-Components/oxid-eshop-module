<?php

namespace Omikron\FactFinder\Oxid\Contract\Api;

interface ResourceInterface
{
    /**
     * @param string $term
     * @param string $channel
     * @param array  $params
     * @param array  $headers
     *
     * @return array
     */
    public function search(string $term, string $channel, array $params = [], array $headers = []): array;

    /**
     * @param string $type
     * @param string $channel
     * @param array  $params
     * @param array  $headers
     *
     * @return array
     */
    public function import(string $type, string $channel, array $params = [], array $headers = []): array;
}
