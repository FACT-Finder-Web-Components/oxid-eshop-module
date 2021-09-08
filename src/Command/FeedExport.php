<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Command;


use OxidEsales\EshopCommunity\Internal\Framework\Console\AbstractShopAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class FeedExport extends AbstractShopAwareCommand implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public const SALES_CHANNEL_ARGUMENT          = 'sales_channel';
    public const SALES_CHANNEL_LANGUAGE_ARGUMENT = 'language';
    public const EXPORT_TYPE_ARGUMENT            = 'export_type';

    public function configure()
    {
        $this->setName('factfinder:feed-export');
        $this->setDescription('Allows to export feed for different data types');
        $this->setHelp('Some help information');
        $this->addArgument(self::SALES_CHANNEL_ARGUMENT, InputArgument::OPTIONAL, 'ID of the sales channel');
        $this->addArgument(self::SALES_CHANNEL_LANGUAGE_ARGUMENT, InputArgument::OPTIONAL, 'ID of the sales channel language');
        $this->addArgument(self::EXPORT_TYPE_ARGUMENT, InputArgument::OPTIONAL, 'Export type');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Test');
    }
}

