<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Export;

interface UploadInterface
{
    public function upload($handle, string $filename);
}
