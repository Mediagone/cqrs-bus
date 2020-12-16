<?php declare(strict_types=1);

namespace Tests\Mediagone\CQRS\Bus\Infrastructure\Command;

use Exception;
use Mediagone\CQRS\Bus\Domain\Command\CommandBus;
use Mediagone\CQRS\Bus\Infrastructure\Command\CommandBusQueue;
use Mediagone\CQRS\Bus\Infrastructure\Command\CommandBusSuffixResolver;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ServiceLocator;


/**
 * @covers \Mediagone\CQRS\Bus\Infrastructure\Command\CommandBusQueue
 */
final class CommandBusQueueTest extends TestCase
{
    //========================================================================================================
    // Init
    //========================================================================================================
    
    private CommandBus $commandBus;
    
    
    public function setUp() : void
    {
        $serviceLocator = new ServiceLocator([
            TestCommand_Handler::class => fn() => new TestCommand_Handler(),
        ]);
        
        $this->commandBus = new CommandBusQueue(
            new CommandBusSuffixResolver($serviceLocator, '_Handler', null)
        );
    }
    
    
    
    //========================================================================================================
    // Tests
    //========================================================================================================
    
    public function test_can_queue_inner_commands() : void
    {
        $bus = $this->commandBus;
        $messages = [];
        
        $bus->do(new TestCommand(static function() use (&$messages, $bus) {
            $bus->do(new TestCommand(static function() use (&$messages) { $messages[] = '2'; }));
            $bus->do(new TestCommand(static function() use (&$messages) { $messages[] = '3'; }));
            $messages[] = '1';
        }));
        
        $bus->do(new TestCommand(static function() use (&$messages) { $messages[] = '4'; }));
        
        self::assertSame(['1', '2', '3', '4'], $messages);
    }
    
    
    public function test_skips_queued_commands_when_an_exception_is_thrown() : void
    {
        $bus = $this->commandBus;
        $messages = [];
        
        $executeNewCommandsThenAppendMessage = static function () use (&$messages, $bus) {
            $bus->do(new TestCommand(static function () use (&$messages) { $messages[] = '2 //should be ignored'; }));
            $bus->do(new TestCommand(static function () use (&$messages) { $messages[] = '3 //should be ignored'; }));
            $messages[] = '1';
            throw new Exception();
        };
        
        try {
            $bus->do(new TestCommand($executeNewCommandsThenAppendMessage));
        }
        catch (Exception $ex) {
            // ignore the exception
        }
        
        self::assertSame(['1'], $messages);
    }
    
    
    public function test_can_resume_handling_commands_after_when_exception_is_thrown() : void
    {
        $bus = $this->commandBus;
        $messages = [];
        
        $executeNewCommandsThenAppendMessage = static function () use (&$messages) {
            $messages[] = '1';
            throw new Exception();
        };
        
        try {
            $bus->do(new TestCommand($executeNewCommandsThenAppendMessage));
        }
        catch (Exception $ex) {
            // ignore the exception
        }
        
        $resumeMessage = static function() use (&$messages) { $messages[] = 'resume after exception'; };
        $bus->do(new TestCommand($resumeMessage));
        
        self::assertSame(['1', 'resume after exception'], $messages);
    }
    
    
    
}
