<?php

declare(strict_types=1);

namespace Tests\Variant\Export\Stream;

use Omikron\FactFinder\Oxid\Export\Stream\Csv;

class CsvVariant extends Csv
{
    /** @var array */
    private $output = [];

    public function addEntity(array $entity): void
    {
        if (isset($entity['DisplayError'])) {
            throw new \Exception($entity['DisplayError']);
        }

        $filename = TEST_DATA_DIRECTORY . 'tmp.csv';
        $file = fopen($filename, 'w');
        fputcsv($file, $entity, $this->delimiter);
        fclose($file);

        $this->output[] = file_get_contents($filename);
    }

    public function getOutput(): array
    {
        return $this->output;
    }
}
