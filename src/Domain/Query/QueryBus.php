<?php declare(strict_types=1);

namespace Mediagone\CQRS\Bus\Domain\Query;


interface QueryBus
{
    /**
     * Routes a query to its executor and returns the result.
     *
     * @template T
     *
     * @param Query<T> $query
     * @return ?T The result of the query.
     */
    public function find(Query $query);
}
