<?php declare(strict_types=1);

namespace Mediagone\CQRS\Bus\Domain\Query;

use LogicException;
use Mediagone\DDD\Doctrine\Specifications\SpecificationRepository;


class SpecificationQueryFetcher implements QueryFetcher
{
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    protected SpecificationRepository $repository;
    
    
    
    //========================================================================================================
    // Constructor
    //========================================================================================================
    
    public function __construct(SpecificationRepository $repository)
    {
        $this->repository = $repository;
    }
    
    
    
    //========================================================================================================
    // Methods
    //========================================================================================================
    
    public function fetch(Query $query)
    {
        if (! $query instanceof SpecificationQuery) {
            throw new LogicException('"'.get_class($query).'" must extends the '.SpecificationQuery::class.' class.');
        }
        
        return $this->repository->find($query);
    }
    
    
    
}
