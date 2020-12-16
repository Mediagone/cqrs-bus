<?php declare(strict_types=1);

namespace Tests\Mediagone\CQRS\Bus\Infrastructure\Event;

use Mediagone\CQRS\Bus\Domain\Event\EventBus;
use Mediagone\CQRS\Bus\Infrastructure\Event\EventBusDispatchingToListeners;
use PHPUnit\Framework\TestCase;
use TypeError;


/**
 * @covers \Mediagone\CQRS\Bus\Infrastructure\Event\EventBusDispatchingToListeners
 */
final class EventBusDispatchingToListenersTest extends TestCase
{
    //========================================================================================================
    // Init
    //========================================================================================================
    
    private EventBus $eventBus;
    
    private TestEventListener $eventListener;
    
    
    public function setUp() : void
    {
        $this->eventListener = new TestEventListener();
        $this->eventBus = new EventBusDispatchingToListeners([$this->eventListener]);
    }
    
    
    
    //========================================================================================================
    // Tests
    //========================================================================================================
    
    public function test_requires_an_array_of_event_listeners_only() : void
    {
        $this->expectException(TypeError::class);
        
        new EventBusDispatchingToListeners([1]);
    }
    
    
    public function test_can_execute_handler() : void
    {
        $command = new TestEvent();
        $this->eventBus->notify($command);
        
        self::assertCount(1, $this->eventListener->getListenedEvents());
        self::assertSame($command, $this->eventListener->getListenedEvents()[0]);
    }
    
    
    public function test_can_execute_multiple_handlers() : void
    {
        $eventListener = new TestEventListener();
        $eventListener2 = new TestEventListener();
        $eventBus = new EventBusDispatchingToListeners([$eventListener, $eventListener2]);
        
        $command = new TestEvent();
        $eventBus->notify($command);
        
        self::assertCount(1, $eventListener->getListenedEvents());
        self::assertSame($command, $eventListener->getListenedEvents()[0]);
        self::assertCount(1, $eventListener2->getListenedEvents());
        self::assertSame($command, $eventListener2->getListenedEvents()[0]);
    }
    
    
    
}
