<?php declare(strict_types=1);

namespace Tests\Mediagone\CQRS\Bus\Infrastructure\Command;

use Mediagone\CQRS\Bus\Domain\Command\CommandBus;
use Mediagone\CQRS\Bus\Domain\Event\EventBus;
use Mediagone\CQRS\Bus\Infrastructure\Command\CommandBusEventsCollector;
use Mediagone\CQRS\Bus\Infrastructure\Command\CommandBusSuffixResolver;
use Mediagone\CQRS\Bus\Infrastructure\Event\EventBusCollectingEvents;
use Mediagone\CQRS\Bus\Infrastructure\Event\EventBusDispatchingToListeners;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Symfony\Component\DependencyInjection\ServiceLocator;
use Tests\Mediagone\CQRS\Bus\Infrastructure\Event\TestEvent;
use Tests\Mediagone\CQRS\Bus\Infrastructure\Event\TestEventListener;


/**
 * @covers \Mediagone\CQRS\Bus\Infrastructure\Command\CommandBusEventsCollector
 */
final class CommandBusEventsCollectorTest extends TestCase
{
    //========================================================================================================
    // Init
    //========================================================================================================
    
    private CommandBus $commandBus;
    
    private EventBus $eventBus;
    
    
    public function setUp() : void
    {
        $this->eventBus = new EventBusCollectingEvents(new EventBusDispatchingToListeners([new TestEventListener()]));
        
        $serviceLocator = new ServiceLocator([
            TestCommand_Handler::class => fn() => new TestCommand_Handler(),
        ]);
    
        $this->commandBus = new CommandBusEventsCollector(
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
        
        $commandBus->do(new TestCommand(static function() use (&$messages, $eventBus) {
            $eventBus->notify(new TestEvent(static function() use (&$messages) { $messages[] = '2 // collected'; }));
            $messages[] = '1';
            $eventBus->notify(new TestEvent(static function() use (&$messages) { $messages[] = '3 // collected'; }));
        }));
        
        $commandBus->do(new TestCommand(static function() use (&$messages) { $messages[] = '4'; }));
        
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
            $commandBus->do(new TestCommand(static function() use (&$messages, $eventBus) {
                $eventBus->notify(new TestEvent(static function() use (&$messages) { $messages[] = '2 // should be ignored'; }));
                $messages[] = '1';
                
                throw new RuntimeException();
                
                $eventBus->dispatch(new TestEvent(static function() use (&$messages) { $messages[] = '3 // should be ignored'; }));
            }));
        }
        catch (RuntimeException $ex) {
            $exceptionRethrown = true;
        }
        
        $commandBus->do(new TestCommand(static function() use (&$messages) {$messages[] = '4'; }));
        
        self::assertTrue($exceptionRethrown);
        self::assertSame(['1', '4'], $messages);
    }
    
    
    
}
