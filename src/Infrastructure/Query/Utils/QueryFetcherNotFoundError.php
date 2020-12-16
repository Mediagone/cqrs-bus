<?php declare(strict_types=1);

namespace Mediagone\CQRS\Bus\Infrastructure\Query\Utils;

use LogicException;
use Mediagone\CQRS\Bus\Domain\Query\Query;
use function get_class;


final class QueryFetcherNotFoundError extends LogicException
{
    //========================================================================================================
    // Constructor
    //========================================================================================================
    
    public function __construct(Query $query)
    {
        parent::__construct('Fetcher class not found for the "' . get_class($query) . '" query. Is it registered and tagged accordingly in the services.yaml file?');
    }
    
    
    
}
