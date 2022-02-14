<?php declare(strict_types=1);

namespace Mediagone\CQRS\Bus\Domain\Query;


interface QueryFetcher
{
    /**
     * @template T
     *
     * @param Query<T> $query
     * @return T The result of the query.
     */
    public function fetch(Query $query);
}
