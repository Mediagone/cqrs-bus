<?php declare(strict_types=1);

namespace Tests\Mediagone\CQRS\Bus\Infrastructure\Query;

use Mediagone\CQRS\Bus\Domain\Query\Query;
use Mediagone\CQRS\Bus\Domain\Query\QueryFetcher;


final class FakeQuery_Fetcher implements QueryFetcher
{
    private array $handleList = [];
    
    
    /**
     * @param FakeQuery $query
     */
    public function fetch(Query $query)
    {
        $this->handleList[] = $query;
        $query();
    }
    
    
    public function getFetchedQueries() : array
    {
        return $this->handleList;
    }
    
    
    
}
