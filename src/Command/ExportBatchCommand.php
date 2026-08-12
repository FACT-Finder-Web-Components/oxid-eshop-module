<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Command;

use Omikron\FactFinder\Oxid\Export\ArticleFeed;
use Omikron\FactFinder\Oxid\Export\Stream\Csv;
use OxidEsales\Eshop\Core\Config;
use OxidEsales\Eshop\Core\Registry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ExportBatchCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->setName('factfinder:export:batch')
            ->setDescription('Internal worker command for exporting a batch of products')
            ->addArgument('shop_id', InputArgument::REQUIRED, 'ID of the shop')
            ->addArgument('language_id', InputArgument::REQUIRED, 'ID of the language')
            ->addArgument('offset', InputArgument::REQUIRED, 'Offset')
            ->addArgument('limit', InputArgument::REQUIRED, 'Limit')
            ->addArgument('file_path', InputArgument::REQUIRED, 'Path to output CSV');
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int {
        $shopId     = (int) $input->getArgument('shop_id');
        $languageId = (int) $input->getArgument('language_id');
        $offset     = (int) $input->getArgument('offset');
        $limit      = (int) $input->getArgument('limit');
        $filePath   = $input->getArgument('file_path');

        Registry::getConfig()->setShopId($shopId);
        Registry::set(Config::class, null);
        Registry::getLang()->setBaseLanguage($languageId);

        $feed = oxNew(ArticleFeed::class);

        $handle = fopen($filePath, 'ab');

        if ($handle === false) {
            throw new \RuntimeException(
                sprintf('Unable to open export file: %s', $filePath)
            );
        }

        try {
            $processedCount = $feed->generateBatch(
                oxNew(Csv::class, $handle),
                $offset,
                $limit,
                $offset === 0
            );

            $result = [
                'count'  => $processedCount,
                'memory' => round(memory_get_usage(true) / 1024 / 1024, 2),
                'peak'   => round(memory_get_peak_usage(true) / 1024 / 1024, 2),
            ];

            $output->write(json_encode($result));

            return Command::SUCCESS;
        } finally {
            fclose($handle);
        }
    }
}
