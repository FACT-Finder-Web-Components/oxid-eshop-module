<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Export;

use Omikron\FactFinder\Oxid\Export\Entity\DataProviderInterface;
use Omikron\FactFinder\Oxid\Export\Entity\ExportEntityInterface;
use Omikron\FactFinder\Oxid\Export\Filter\TextFilter;
use Omikron\FactFinder\Oxid\Export\Stream\StreamInterface;
use Omikron\FactFinder\Oxid\Utilities\FfLogger;
use OxidEsales\Eshop\Core\Registry;

class Exporter implements ExporterInterface
{
    /** @var TextFilter */
    private $filter;

    /** @var bool */
    private $proceedWhileError;

    /** @var FfLogger */
    private $logger;

    public function __construct()
    {
        $this->filter            = oxNew(TextFilter::class);
        $this->proceedWhileError = boolval(Registry::getConfig()->getConfigParam('ffIsProceedWhileError'));
        $this->logger            = new FfLogger('exporter');
    }

    public function exportEntities(StreamInterface $stream, DataProviderInterface $dataProvider, array $columns): void
    {
        $emptyRecord = array_combine($columns, array_fill(0, count($columns), ''));

        foreach ($dataProvider->getEntities() as $entity) {
            try {
                $entityData = array_merge($emptyRecord, array_intersect_key($entity->toArray(), $emptyRecord)); // phpcs:ignore
                $stream->addEntity($this->prepare($entityData));
            } catch (\Throwable $e) {
                $this->handleError($e, $entity);
            }
        }
    }

    private function handleError(\Throwable $exception, ExportEntityInterface $entity): void
    {
        if ($this->proceedWhileError === false) {
            throw $exception;
        }

        $entity = array_filter($entity->toArray(), function (string $field): bool {
            return in_array($field, [
                'ProductNumber',
                'Name',
                'Id',
                'ShopId',
                'ParentId',
                'RootId',
            ]);
        }, ARRAY_FILTER_USE_KEY);
        $this->logger->error($exception->getMessage(), ['entity' => $entity]);
    }

    private function prepare(array $data): array
    {
        return array_map([$this->filter, 'filterValue'], $data);
    }
}
