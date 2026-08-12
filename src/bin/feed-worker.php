<?php

declare(strict_types=1);

use Omikron\FactFinder\Oxid\Export\FeedTypes;
use Omikron\FactFinder\Oxid\Model\Export\UploadFactory;
use Omikron\FactFinder\Oxid\Model\Api\PushImport;
use OxidEsales\Eshop\Core\Config;
use OxidEsales\Eshop\Core\Registry;
use Symfony\Component\Process\Process;

$options = getopt('s:t:l:');

$shopId     = $options['s'] ?? 0;
$exportType = $options['t'] ?? 'product';
$languageId = $options['l'] ?? 0;

if (!$shopId && $shopId !== 0) {
    throw new RuntimeException('Please specify the shop ID using the "s" parameter!');
}

require_once dirname(__FILE__) . '/../../../../../source/bootstrap.php';

define('OX_IS_ADMIN', true);

$filePath = null;
$projectRoot = dirname(__FILE__) . '/../../../../../';
$consolePath = $projectRoot . 'vendor/bin/oe-console';

if (!is_file($consolePath)) {
    throw new RuntimeException(sprintf('oe-console not found at: %s', $consolePath));
}

try {
    if ($exportType !== 'product') {
        throw new RuntimeException(
            sprintf(
                'The queued export supports only product export. Given: "%s".',
                $exportType
            )
        );
    }

    Registry::getConfig()->setShopId($shopId);
    Registry::set(Config::class, null);
    Registry::getLang()->setBaseLanguage($languageId);

    $feedFQN = FeedTypes::getFeedType($exportType);
    $feed    = oxNew($feedFQN);

    $filePath = tempnam(sys_get_temp_dir(), 'factfinder_export_');

    if ($filePath === false) {
        throw new RuntimeException('Unable to create temporary export file.');
    }

    $batchSize = 100;
    $offset    = 0;

    $phpBinary = PHP_BINARY;

    echo "Starting queued product export...\n";
    echo "Shop ID: {$shopId}\n";
    echo "Language ID: {$languageId}\n";
    echo "Batch size: {$batchSize}\n";
    echo "Output file: {$filePath}\n\n";

    while (true) {
        echo sprintf(
            "[%s] Processing batch: offset=%d, limit=%d\n",
            date('H:i:s'),
            $offset,
            $batchSize
        );

        $process = new Process([
            $phpBinary,
            $consolePath,
            'factfinder:export:batch',
            (string) $shopId,
            (string) $languageId,
            (string) $offset,
            (string) $batchSize,
            $filePath,
        ]);

        $process->setTimeout(600);
        $process->run();

        if (!$process->isSuccessful()) {
            echo sprintf(
                "[%s] Batch failed at offset %d.\n",
                date('H:i:s'),
                $offset
            );

            $errorOutput = trim($process->getErrorOutput());

            if ($errorOutput !== '') {
                echo $errorOutput . "\n";
            }

            throw new RuntimeException(
                sprintf(
                    'Product export failed at offset %d.',
                    $offset
                )
            );
        }

        $rawOutput = trim($process->getOutput());
        preg_match('/\{.*\}/s', $rawOutput, $matches);
        $jsonOutput = $matches[0] ?? null;

        if ($jsonOutput === null) {
            throw new RuntimeException(
                sprintf(
                    'Unable to parse worker output for batch at offset %d. Output: %s',
                    $offset,
                    $rawOutput
                )
            );
        }

        $result = json_decode(
            $jsonOutput,
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        $processedCount = (int) ($result['count'] ?? 0);
        $memory         = (float) ($result['memory'] ?? 0);
        $peakMemory     = (float) ($result['peak'] ?? 0);

        echo sprintf(
            "[%s] Batch completed: %d records, memory %.2f MB, peak %.2f MB\n",
            date('H:i:s'),
            $processedCount,
            $memory,
            $peakMemory
        );

        if ($processedCount === 0) {
            break;
        }

        $offset += $batchSize;
    }

    echo sprintf(
        "\n[%s] Product export completed.\n",
        date('H:i:s')
    );

    $uploader = oxNew(UploadFactory::class)->create();
    $handle = fopen($filePath, 'rb');

    if ($handle === false) {
        throw new RuntimeException(
            sprintf(
                'Unable to open generated export file: %s',
                $filePath
            )
        );
    }

    try {
        echo sprintf(
            "[%s] Uploading feed...\n",
            date('H:i:s')
        );

        $uploader->upload(
            $handle,
            $feed->getFileName()
        );
    } finally {
        fclose($handle);
    }

    echo sprintf(
        "[%s] Feed uploaded successfully.\n",
        date('H:i:s')
    );

    $pushImport = oxNew(PushImport::class);

    echo sprintf(
        "[%s] Starting push import...\n",
        date('H:i:s')
    );

    $pushImport->execute();

    echo sprintf(
        "[%s] Push import completed.\n",
        date('H:i:s')
    );
} finally {
    if ($filePath !== null && file_exists($filePath)) {
        unlink($filePath);
    }
}
