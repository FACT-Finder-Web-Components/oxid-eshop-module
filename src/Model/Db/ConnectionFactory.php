<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Db;

use Doctrine\DBAL\Connection;
use OxidEsales\Eshop\Core\Database\Adapter\Doctrine\Database;
use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Core\Exception\DatabaseConnectionException;

/**
 * @internal
 */
class ConnectionFactory
{
    /**
     * @return Connection
     * @throws DatabaseConnectionException
     * @throws \ReflectionException
     */
    public static function get(): Connection
    {
        $database = DatabaseProvider::getDb();
        $r = new \ReflectionMethod(Database::class, 'getConnection');
        $r->setAccessible(true);

        return $r->invoke($database);
    }
}
