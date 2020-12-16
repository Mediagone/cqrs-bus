<?php declare(strict_types=1);

namespace Mediagone\CQRS\Bus\Domain\Query;


interface QueryBus
{
    /**
     * Routes a query to its executor and returns the result.
     * @return mixed The result of the query.
     */
    public function find(Query $query);
}
