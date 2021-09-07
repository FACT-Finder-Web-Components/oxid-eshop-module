<?php


namespace Omikron\FactFinder\Oxid\Command;


use OxidEsales\EshopCommunity\Internal\Framework\Console\AbstractShopAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FeedExport extends AbstractShopAwareCommand
{
    public function configure()
    {
        $this->setName('factfinder:feed-export');
        $this->setDescription('Allows to export feed for different data types');
        $this->setHelp('Some help information');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Test');
    }
}

