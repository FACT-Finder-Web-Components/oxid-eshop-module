<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Model\Db;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\Expression\CompositeExpression;
use Doctrine\DBAL\Query\QueryBuilder;
use Omikron\FactFinder\Oxid\Contract\Db\JoinInterface;
use Omikron\FactFinder\Oxid\Contract\Db\SelectInterface;
use Omikron\FactFinder\Oxid\Contract\Db\WhereInterface;
use OxidEsales\Eshop\Core\DatabaseProvider;

class Records
{
    /** @var SelectInterface */
    private $select;

    /** @var string */
    private $from;

    /** @var JoinInterface */
    private $join;

    /** @var WhereInterface */
    private $where;

    /** @var string */
    private $groupBy;

    /** @var QueryBuilder */
    private $queryBuilder;

    /** @var Connection */
    private $connection;

    /** @var string */
    private $query = '';

    public function __construct(
        SelectInterface $select,
        string $from,
        JoinInterface $join,
        WhereInterface $where,
        string $groupBy = ''
    ) {
        $this->select = $select;
        $this->from = $from;
        $this->join = $join;
        $this->where = $where;
        $this->groupBy = $groupBy;
        $this->connection = ConnectionFactory::get();
        $this->queryBuilder = $this->connection->createQueryBuilder();
    }

    public function getRecords(): array
    {
        $this->connection->setFetchMode(DatabaseProvider::FETCH_MODE_ASSOC);
        return $this->connection->fetchAll($this->getQuery());
    }

    public function getColumns(): array
    {
        if ($this->query == '') {
            $this->getQuery();
        }
        return array_keys($this->queryBuilder->getQueryParts()['select']);
    }

    private function getQuery(): string
    {
        if ($this->query == '') {
            $this->queryBuilder->select($this->getFields($this->select))
                ->from($this->from, $this->from)
                ->where(new CompositeExpression(CompositeExpression::TYPE_AND, $this->where->getClause()))
                ->add('join', $this->join->getJoin());
            if ($this->groupBy != '') {
                $this->queryBuilder->groupBy($this->groupBy);
            }
            $this->query = $this->queryBuilder->getSQL();
        }
        return $this->query;
    }

    private function getFields(SelectInterface $select): array
    {
        $fields = [];
        foreach ($select->getFields() as $key => $value) {
            $fields[$key] = $value . ' AS ' . $key;
        }
        return $fields;
    }
}
