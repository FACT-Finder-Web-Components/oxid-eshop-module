<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Export\Http;

use SplFileObject as File;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CsvResponse extends StreamedResponse
{
    /** @var File */
    private $handle;

    public function __construct(File $handle, string $fileName, int $status = 200, array $headers = [])
    {
        parent::__construct(null, $status, $headers);
        $this->handle = $handle;
        $this->headers->set('Content-Disposition', $this->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $fileName
        ));
        $this->headers->set('Content-Type', 'text/csv; charset=utf-8');
    }

    /**
     * Sends content for the current web response.
     *
     * @return $this
     */
    public function sendContent()
    {
        $this->handle->fseek(0);
        while (!$this->handle->eof()) {
            $buffer = $this->handle->fread(1024);
            echo $buffer;
            flush();
        }

        return $this;
    }
}
