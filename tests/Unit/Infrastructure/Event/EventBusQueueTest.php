<?php declare(strict_types=1);

namespace Tests\Mediagone\CQRS\Bus\Infrastructure\Event;

use Mediagone\CQRS\Bus\Infrastructure\Event\EventBusDispatcher;
use Mediagone\CQRS\Bus\Infrastructure\Event\EventBusQueue;
use PHPUnit\Framework\TestCase;


/**
 * @covers \Mediagone\CQRS\Bus\Infrastructure\Event\EventBusQueue
 */
final class EventBusQueueTest extends TestCase
{
    //========================================================================================================
    // Tests
    //========================================================================================================
    
    public function test_can_dispatch_an_event() : void
    {
        $listener = new FakeEventListener();
        $eventBus = new EventBusQueue(new EventBusDispatcher(new FakeListenerProvider([$listener])));
    
        $event = new FakeEvent();
        $eventBus->dispatch($event);
        
        self::assertCount(1, $listener->getListenedEvents());
        self::assertSame($event, $listener->getListenedEvents()[0]);
    }
    
    
    public function test_can_collect_events() : void
    {
        $listener = new FakeEventListener();
        $eventBus = new EventBusQueue(new EventBusDispatcher(new FakeListenerProvider([$listener])));
        
        $event = new FakeEvent();
        $eventBus->startCollecting();
        $eventBus->dispatch($event);
        
        self::assertCount(0, $listener->getListenedEvents());
    }
    
    
    public function test_can_release_collected_events() : void
    {
        $listener = new FakeEventListener();
        $eventBus = new EventBusQueue(new EventBusDispatcher(new FakeListenerProvider([$listener])));
        
        $event = new FakeEvent();
        $event2 = new FakeEvent();
    
        $eventBus->startCollecting();
        $eventBus->dispatch($event);
        $eventBus->dispatch($event2);
        
        self::assertCount(0, $listener->getListenedEvents());
    
        $eventBus->releaseCollected();
        self::assertCount(2, $listener->getListenedEvents());
        self::assertSame($event, $listener->getListenedEvents()[0]);
        self::assertSame($event2, $listener->getListenedEvents()[1]);
    }
    
    
    public function test_can_discard_collected_event() : void
    {
        $listener = new FakeEventListener();
        $eventBus = new EventBusQueue(new EventBusDispatcher(new FakeListenerProvider([$listener])));
        
        $event = new FakeEvent();
    
        $eventBus->startCollecting();
        $eventBus->dispatch($event);
        
        self::assertCount(0, $listener->getListenedEvents());
    
        $eventBus->discardCollected();
        self::assertCount(0, $listener->getListenedEvents());
    }
    
    
    
}
