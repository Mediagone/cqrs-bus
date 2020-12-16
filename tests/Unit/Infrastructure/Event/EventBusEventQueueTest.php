<?php declare(strict_types=1);

namespace Tests\Mediagone\CQRS\Bus\Infrastructure\Event;

use Mediagone\CQRS\Bus\Domain\Event\EventBus;
use Mediagone\CQRS\Bus\Infrastructure\Event\EventBusEventDispatcher;
use Mediagone\CQRS\Bus\Infrastructure\Event\EventBusEventQueue;
use PHPUnit\Framework\TestCase;


/**
 * @covers \Mediagone\CQRS\Bus\Infrastructure\Event\EventBusEventQueue
 */
final class EventBusEventQueueTest extends TestCase
{
    //========================================================================================================
    // Init
    //========================================================================================================
    
    private EventBus $eventBus;
    
    private TestEventListener $eventListener;
    
    
    public function setUp() : void
    {
        $this->eventListener = new TestEventListener();
        $this->eventBus = new EventBusEventQueue(new EventBusEventDispatcher([$this->eventListener]));
    }
    
    
    
    //========================================================================================================
    // Tests
    //========================================================================================================
    
    public function test_can_dispatch_an_event() : void
    {
        $event = new TestEvent();
        $this->eventBus->notify($event);
        
        self::assertCount(1, $this->eventListener->getListenedEvents());
        self::assertSame($event, $this->eventListener->getListenedEvents()[0]);
    }
    
    
    public function test_can_collect_an_event() : void
    {
        $event = new TestEvent();
        
        $this->eventBus->startCollecting();
        $this->eventBus->notify($event);
        
        self::assertCount(0, $this->eventListener->getListenedEvents());
    }
    
    
    public function test_can_release_collected_events() : void
    {
        $event = new TestEvent();
        $event2 = new TestEvent();
        
        $this->eventBus->startCollecting();
        $this->eventBus->notify($event);
        $this->eventBus->notify($event2);
        
        self::assertCount(0, $this->eventListener->getListenedEvents());
        
        $this->eventBus->releaseCollected();
        self::assertCount(2, $this->eventListener->getListenedEvents());
        self::assertSame($event, $this->eventListener->getListenedEvents()[0]);
        self::assertSame($event2, $this->eventListener->getListenedEvents()[1]);
    }
    
    
    public function test_can_discard_collected_event() : void
    {
        $event = new TestEvent();
        
        $this->eventBus->startCollecting();
        $this->eventBus->notify($event);
        
        self::assertCount(0, $this->eventListener->getListenedEvents());
        
        $this->eventBus->discardCollected();
        self::assertCount(0, $this->eventListener->getListenedEvents());
    }
    
    
    
}
