<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Export;

use SplFileObject as File;

class FileWriter
{

    public function save(File $file, string $filePath)
    {
        $exportFile = new File($filePath, 'w');

        while (($data = $file->fgetcsv(';')) != false) {
            $exportFile->fputcsv($data, ';');
        }
    }
}
