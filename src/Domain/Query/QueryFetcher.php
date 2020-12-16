<?php declare(strict_types=1);

namespace Mediagone\CQRS\Bus\Domain\Query;


interface QueryFetcher
{
    /**
     * @return mixed The result of the query.
     */
    public function fetch(Query $query);
    
}
