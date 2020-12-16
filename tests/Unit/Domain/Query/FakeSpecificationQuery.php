<?php declare(strict_types=1);

namespace Tests\Mediagone\CQRS\Bus\Domain\Query;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Mediagone\CQRS\Bus\Domain\Query\SpecificationQuery;
use Mediagone\DDD\Doctrine\Specifications\Specification;
use Mediagone\DDD\Doctrine\Specifications\SpecificationRepositoryResult;


final class FakeSpecificationQuery extends SpecificationQuery
{
    public static function create() : self
    {
        return new self(
            new class() implements Specification {
                public function modifyBuilder(QueryBuilder $builder) : void { }
                public function modifyQuery(Query $query) : void { }
            },
            SpecificationRepositoryResult::MANY_OBJECTS
        );
    }
    
}
