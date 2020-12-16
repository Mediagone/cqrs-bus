<?php declare(strict_types=1);

namespace Tests\Mediagone\CQRS\Bus\Infrastructure\Event;

use Mediagone\CQRS\Bus\Domain\Event\EventBus;
use Mediagone\CQRS\Bus\Infrastructure\Event\EventBusDispatcher;
use Mediagone\CQRS\Bus\Infrastructure\Event\EventBusQueue;
use PHPUnit\Framework\TestCase;


/**
 * @covers \Mediagone\CQRS\Bus\Infrastructure\Event\EventBusQueue
 */
final class EventBusQueueTest extends TestCase
{
    //========================================================================================================
    // Init
    //========================================================================================================
    
    private EventBus $eventBus;
    
    private FakeEventListener $eventListener;
    
    
    public function setUp() : void
    {
        $this->eventListener = new FakeEventListener();
        $this->eventBus = new EventBusQueue(new EventBusDispatcher([$this->eventListener]));
    }
    
    
    
    //========================================================================================================
    // Tests
    //========================================================================================================
    
    public function test_can_dispatch_an_event() : void
    {
        $event = new FakeEvent();
        $this->eventBus->notify($event);
        
        self::assertCount(1, $this->eventListener->getListenedEvents());
        self::assertSame($event, $this->eventListener->getListenedEvents()[0]);
    }
    
    
    public function test_can_collect_an_event() : void
    {
        $event = new FakeEvent();
        
        $this->eventBus->startCollecting();
        $this->eventBus->notify($event);
        
        self::assertCount(0, $this->eventListener->getListenedEvents());
    }
    
    
    public function test_can_release_collected_events() : void
    {
        $event = new FakeEvent();
        $event2 = new FakeEvent();
        
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
        $event = new FakeEvent();
        
        $this->eventBus->startCollecting();
        $this->eventBus->notify($event);
        
        self::assertCount(0, $this->eventListener->getListenedEvents());
        
        $this->eventBus->discardCollected();
        self::assertCount(0, $this->eventListener->getListenedEvents());
    }
    
    
    
}
