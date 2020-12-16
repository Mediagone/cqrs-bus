<?php declare(strict_types=1);

namespace Tests\Mediagone\CQRS\Bus\Infrastructure\Command;

use Mediagone\CQRS\Bus\Infrastructure\Command\CommandBusSuffixResolver;
use Mediagone\CQRS\Bus\Infrastructure\Command\Utils\CommandHandlerNotFoundError;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ServiceLocator;


/**
 * @covers \Mediagone\CQRS\Bus\Infrastructure\Command\CommandBusSuffixResolver
 */
final class CommandBusSuffixResolverTest extends TestCase
{
    //========================================================================================================
    // Tests
    //========================================================================================================
    
    public function test_can_execute_handler() : void
    {
        $commandHandler = new TestCommand_Handler();
        $serviceLocator = new ServiceLocator([
            TestCommand_Handler::class => fn() => $commandHandler,
        ]);
        $bus = new CommandBusSuffixResolver($serviceLocator, '_Handler', null);
        
        $command = new TestCommand();
        $bus->do($command);
        self::assertCount(1, $commandHandler->getHandledCommands());
        self::assertSame($command, $commandHandler->getHandledCommands()[0]);
    }
    
    
    public function test_throws_an_exception_when_no_handler() : void
    {
        $command = new TestCommand();
        
        $serviceLocator = new ServiceLocator([]);
        $bus = new CommandBusSuffixResolver($serviceLocator, '_Handler', null);
        
        $this->expectException(CommandHandlerNotFoundError::class);
        $bus->do($command);
    }
    
    
    
}
