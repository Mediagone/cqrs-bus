<?php declare(strict_types=1);

namespace Tests\Mediagone\CQRS\Bus\Infrastructure\Event;

use Mediagone\CQRS\Bus\Infrastructure\Event\EventBusDispatcher;
use PHPUnit\Framework\TestCase;


/**
 * @covers \Mediagone\CQRS\Bus\Infrastructure\Event\EventBusDispatcher
 */
final class EventBusDispatcherTest extends TestCase
{
    //========================================================================================================
    // Tests
    //========================================================================================================
    
    // public function test_requires_an_array_of_event_listeners_only() : void
    // {
    //     $this->expectException(TypeError::class);
    //    
    //     new EventBusDispatcher([1]);
    // }
    
    
    public function test_can_notify_listener() : void
    {
        $listener = new FakeEventListener();
        $eventBus = new EventBusDispatcher(new FakeListenerProvider([$listener]));
    
        $event = new FakeEvent();
        $eventBus->dispatch($event);
        
        self::assertCount(1, $listener->getListenedEvents());
        self::assertSame($event, $listener->getListenedEvents()[0]);
    }
    
    
    public function test_can_notify_multiple_listeners() : void
    {
        $eventListener = new FakeEventListener();
        $eventListener2 = new FakeEventListener();
        $eventBus = new EventBusDispatcher(new FakeListenerProvider([$eventListener, $eventListener2]));
        
        $event = new FakeEvent();
        $eventBus->dispatch($event);
        
        self::assertCount(1, $eventListener->getListenedEvents());
        self::assertSame($event, $eventListener->getListenedEvents()[0]);
        self::assertCount(1, $eventListener2->getListenedEvents());
        self::assertSame($event, $eventListener2->getListenedEvents()[0]);
    }
    
    
    
}
