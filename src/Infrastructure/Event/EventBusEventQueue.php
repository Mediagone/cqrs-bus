<?php declare(strict_types=1);

namespace Mediagone\CQRS\Bus\Infrastructure\Event;

use Mediagone\CQRS\Bus\Domain\Event\Event;
use Mediagone\CQRS\Bus\Domain\Event\EventBus;


class EventBusEventQueue implements EventBus
{
    //========================================================================================================
    // Fields
    //========================================================================================================
    
    private EventBus $innerBus;
    
    private array $eventQueue = [];
    
    private bool $isCollecting = false;
    
    
    
    //========================================================================================================
    // Constructor
    //========================================================================================================
    
    public function __construct(EventBus $innerBus)
    {
        $this->innerBus = $innerBus;
    }
    
    
    
    //========================================================================================================
    // Methods
    //========================================================================================================
    
    /**
     * @todo Check if no event is created and dispatched from inside an event listener?
     */
    public function notify(Event $event) : void
    {
        $this->eventQueue[] = $event;
        
        if (!$this->isCollecting) {
            $this->dispatchCollectedEvents();
        }
    }
    
    
    /**
     *
     */
    public function startCollecting() : void
    {
        $this->isCollecting = true;
    }
    
    
    /**
     *
     */
    public function releaseCollected() : void
    {
        $this->dispatchCollectedEvents();
        $this->isCollecting = false;
    }
    
    
    /**
     *
     */
    public function discardCollected() : array
    {
        $collectedEvents = $this->eventQueue;
        
        $this->eventQueue = [];
        $this->isCollecting = false;
        
        return $collectedEvents;
    }
    
    
    
    //========================================================================================================
    // Helpers
    //========================================================================================================
    
    private function dispatchCollectedEvents() : void
    {
        while ($event = array_shift($this->eventQueue)) {
            $this->innerBus->notify($event);
        }
    }
    
    
    
}
