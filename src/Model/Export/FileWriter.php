<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Export;

class FileWriter
{
    /**
     * @param resource $file
     * @param string   $filePath
     */
    public function save($file, string $filePath)
    {
        $exportFile = fopen($filePath, 'w');
        while ($data = fgetcsv($file, 0, ';')) {
            fputcsv($exportFile, $data, ';');
        }
        fclose($exportFile);
    }
}
