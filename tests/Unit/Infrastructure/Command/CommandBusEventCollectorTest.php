<?php declare(strict_types=1);

namespace Tests\Mediagone\CQRS\Bus\Infrastructure\Command;

use Mediagone\CQRS\Bus\Domain\Command\CommandBus;
use Mediagone\CQRS\Bus\Domain\Event\EventBus;
use Mediagone\CQRS\Bus\Infrastructure\Command\CommandBusEventCollector;
use Mediagone\CQRS\Bus\Infrastructure\Command\CommandBusSuffixResolver;
use Mediagone\CQRS\Bus\Infrastructure\Event\EventBusQueue;
use Mediagone\CQRS\Bus\Infrastructure\Event\EventBusDispatcher;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Symfony\Component\DependencyInjection\ServiceLocator;
use Tests\Mediagone\CQRS\Bus\Infrastructure\Event\FakeEvent;
use Tests\Mediagone\CQRS\Bus\Infrastructure\Event\FakeEventListener;
use Tests\Mediagone\CQRS\Bus\Infrastructure\Event\FakeListenerProvider;


/**
 * @covers \Mediagone\CQRS\Bus\Infrastructure\Command\CommandBusEventCollector
 */
final class CommandBusEventCollectorTest extends TestCase
{
    //========================================================================================================
    // Init
    //========================================================================================================
    
    private CommandBus $commandBus;
    
    private EventBus $eventBus;
    
    
    public function setUp() : void
    {
        $eventListener = new FakeEventListener();
        $eventBus = new EventBusDispatcher(new FakeListenerProvider([$eventListener]));
        $this->eventBus = new EventBusQueue($eventBus);
        
        $serviceLocator = new ServiceLocator([
            FakeCommand_Handler::class => fn() => new FakeCommand_Handler(),
        ]);
    
        $this->commandBus = new CommandBusEventCollector(
            new CommandBusSuffixResolver($serviceLocator, '_Handler', null),
            $this->eventBus
        );
    }
    
    
    
    //========================================================================================================
    // Tests
    //========================================================================================================
    
    
    public function test_can_collect_events() : void
    {
        $eventBus = $this->eventBus;
        $commandBus = $this->commandBus;
        $messages = [];
        
        $commandBus->do(new FakeCommand(static function() use (&$messages, $eventBus) {
            $eventBus->dispatch(new FakeEvent(static function() use (&$messages) { $messages[] = '2 // collected'; }));
            $messages[] = '1';
            $eventBus->dispatch(new FakeEvent(static function() use (&$messages) { $messages[] = '3 // collected'; }));
        }));
        
        $commandBus->do(new FakeCommand(static function() use (&$messages) { $messages[] = '4'; }));
        
        self::assertSame([
            //'start collecting events',
            //'queue event (2)',
            '1',
            //'queue event (3)',
            //'release collected events',
            '2 // collected',
            '3 // collected',
            //'start collecting events',
            '4',
            //'release collected events',
        ], $messages);
    }
    
    
    public function test_discards_collected_events_when_an_exception_is_thrown() : void
    {
        $eventBus = $this->eventBus;
        $commandBus = $this->commandBus;
        $messages = [];
        $exceptionRethrown = false;
        
        try {
            $commandBus->do(new FakeCommand(static function() use (&$messages, $eventBus) {
                $eventBus->dispatch(new FakeEvent(static function() use (&$messages) { $messages[] = '2 // should be ignored'; }));
                $messages[] = '1';
                
                throw new RuntimeException();
                
                $eventBus->dispatch(new FakeEvent(static function() use (&$messages) { $messages[] = '3 // should be ignored'; }));
            }));
        }
        catch (RuntimeException $ex) {
            $exceptionRethrown = true;
        }
        
        $commandBus->do(new FakeCommand(static function() use (&$messages) {$messages[] = '4'; }));
        
        self::assertTrue($exceptionRethrown);
        self::assertSame(['1', '4'], $messages);
    }
    
    
    
}
