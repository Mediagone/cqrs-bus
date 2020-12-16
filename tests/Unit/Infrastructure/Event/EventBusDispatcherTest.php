<?php declare(strict_types=1);

namespace Tests\Mediagone\CQRS\Bus\Infrastructure\Event;

use Mediagone\CQRS\Bus\Domain\Event\EventBus;
use Mediagone\CQRS\Bus\Infrastructure\Event\EventBusDispatcher;
use PHPUnit\Framework\TestCase;
use TypeError;


/**
 * @covers \Mediagone\CQRS\Bus\Infrastructure\Event\EventBusDispatcher
 */
final class EventBusDispatcherTest extends TestCase
{
    //========================================================================================================
    // Init
    //========================================================================================================
    
    private EventBus $eventBus;
    
    private FakeEventListener $eventListener;
    
    
    public function setUp() : void
    {
        $this->eventListener = new FakeEventListener();
        $this->eventBus = new EventBusDispatcher([$this->eventListener]);
    }
    
    
    
    //========================================================================================================
    // Tests
    //========================================================================================================
    
    public function test_requires_an_array_of_event_listeners_only() : void
    {
        $this->expectException(TypeError::class);
        
        new EventBusDispatcher([1]);
    }
    
    
    public function test_can_execute_handler() : void
    {
        $command = new FakeEvent();
        $this->eventBus->notify($command);
        
        self::assertCount(1, $this->eventListener->getListenedEvents());
        self::assertSame($command, $this->eventListener->getListenedEvents()[0]);
    }
    
    
    public function test_can_execute_multiple_handlers() : void
    {
        $eventListener = new FakeEventListener();
        $eventListener2 = new FakeEventListener();
        $eventBus = new EventBusDispatcher([$eventListener, $eventListener2]);
        
        $command = new FakeEvent();
        $eventBus->notify($command);
        
        self::assertCount(1, $eventListener->getListenedEvents());
        self::assertSame($command, $eventListener->getListenedEvents()[0]);
        self::assertCount(1, $eventListener2->getListenedEvents());
        self::assertSame($command, $eventListener2->getListenedEvents()[0]);
    }
    
    
    
}
