<?php declare(strict_types=1);

namespace Mediagone\CQRS\Bus\Infrastructure\Query;

use InvalidArgumentException;
use Mediagone\CQRS\Bus\Domain\Query\Query;
use Mediagone\CQRS\Bus\Domain\Query\QueryBus;
use Mediagone\CQRS\Bus\Infrastructure\Query\Utils\QueryFetcherNotFoundError;
use Symfony\Component\DependencyInjection\ServiceLocator;
use function get_class;
use function trim;


/**
 * Finds and executes the matching QueryFetcher to handle the Query.
 */
final class QueryBusSuffixResolver implements QueryBus
{
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    private ServiceLocator $serviceLocator;
    
    private ?QueryBus $next;
    
    private string $suffix;
    
    
    
    //========================================================================================================
    // Constructor
    //========================================================================================================
    
    public function __construct(ServiceLocator $serviceLocator, string $suffix, ?QueryBus $next)
    {
        $this->serviceLocator = $serviceLocator;
        $this->suffix = trim($suffix);
        $this->next = $next;
        
        if ($this->suffix === '') {
            throw new InvalidArgumentException('Query fetcher suffix must be specified.');
        }
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
        $fetcherClassName = get_class($query).$this->suffix;
        
        if ($this->serviceLocator->has($fetcherClassName)) {
            $fetcher = $this->serviceLocator->get($fetcherClassName);
            return $fetcher->find($query);
        }
        
        if ($this->next === null) {
            throw new QueryFetcherNotFoundError($query);
        }
        
        return $this->next->find($query);
    }
    
    
    
}
