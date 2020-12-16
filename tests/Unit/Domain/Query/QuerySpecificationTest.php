<?php declare(strict_types=1);

namespace Tests\Mediagone\CQRS\Bus\Domain\Query;

use Mediagone\CQRS\Bus\Domain\Query\Query;
use Mediagone\CQRS\Bus\Domain\Query\SpecificationQuery;
use Mediagone\DDD\Doctrine\Specifications\SpecificationCollection;
use PHPUnit\Framework\TestCase;


/**
 * @covers \Mediagone\CQRS\Bus\Domain\Query\SpecificationQuery
 */
final class QuerySpecificationTest extends TestCase
{
    //========================================================================================================
    // Tests
    //========================================================================================================
    
    public function test_abstract_class_can_be_extended() : void
    {
        $query = FakeSpecificationQuery::create();
        
        self::assertInstanceOf(SpecificationQuery::class, $query);
        self::assertInstanceOf(SpecificationCollection::class, $query);
        self::assertInstanceOf(Query::class, $query);
    }
    
    
    
}
