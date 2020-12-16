<?php declare(strict_types=1);

namespace Mediagone\CQRS\Bus\Infrastructure\Query;

use Mediagone\CQRS\Bus\Domain\Query\Query;
use Mediagone\CQRS\Bus\Domain\Query\QueryBus;
use Mediagone\CQRS\Bus\Domain\Query\SpecificationQuery;
use Mediagone\CQRS\Bus\Infrastructure\Query\Utils\QueryFetcherNotFoundError;
use Mediagone\DDD\Doctrine\Specifications\SpecificationRepository;


/**
 * Finds and executes the matching QueryFetcher to handle the Query.
 */
final class QueryBusSpecificationResolver implements QueryBus
{
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    private ?QueryBus $next;
    
    protected SpecificationRepository $repository;
    
    
    
    //========================================================================================================
    // Constructor
    //========================================================================================================
    
    public function __construct(SpecificationRepository $repository, ?QueryBus $next)
    {
        $this->repository = $repository;
        $this->next = $next;
    }
    
    
    
    //========================================================================================================
    // Methods
    //========================================================================================================
    
    /**
     * Routes a query to its executor.
     *
     * @throws QueryFetcherNotFoundError Thrown if no fetcher is found for the query.
     * @return mixed
     */
    public function find(Query $query)
    {
        if ($query instanceof SpecificationQuery) {
            return $this->repository->find($query);
        }
        
        if ($this->next === null) {
            throw new QueryFetcherNotFoundError($query);
        }
        
        return $this->next->find($query);
    }
    
    
    
}
