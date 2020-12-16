<?php declare(strict_types=1);

namespace Tests\Mediagone\CQRS\Bus\Infrastructure\Query;

use Mediagone\CQRS\Bus\Domain\Query\QueryBus;
use Mediagone\CQRS\Bus\Infrastructure\Query\QueryBusSuffixResolver;
use Mediagone\CQRS\Bus\Infrastructure\Query\Utils\QueryFetcherNotFoundError;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ServiceLocator;
use function get_class;


/**
 * @covers \Mediagone\CQRS\Bus\Infrastructure\Query\QueryBusSuffixResolver
 */
final class QueryBusSuffixResolverTest extends TestCase
{
    //========================================================================================================
    // Init
    //========================================================================================================
    
    private QueryBus $queryBus;
    
    private TestQuery_Fetcher $queryFetcher;
    
    
    public function setUp() : void
    {
        $queryFetcher = new TestQuery_Fetcher();
        $serviceLocator = new ServiceLocator([
            get_class($queryFetcher) => static function() use($queryFetcher) { return $queryFetcher; },
        ]);
        
        $this->queryFetcher = $queryFetcher;
        $this->queryBus = new QueryBusSuffixResolver($serviceLocator, '_Fetcher', null);
    }
    
    
    
    //========================================================================================================
    // Tests
    //========================================================================================================
    
    public function test_can_execute_fetcher() : void
    {
        $query = new TestQuery();
        $this->queryBus->find($query);
        
        self::assertCount(1, $this->queryFetcher->getFetchedQueries());
        self::assertSame($query, $this->queryFetcher->getFetchedQueries()[0]);
    }
    
    
    public function test_throws_an_exception_when_no_fetcher() : void
    {
        $command = new TestQueryWithoutFetcher();
        
        $this->expectException(QueryFetcherNotFoundError::class);
        $this->queryBus->find($command);
    }
    
    
    
}
