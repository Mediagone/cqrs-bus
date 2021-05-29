<?php declare(strict_types=1);

namespace Mediagone\CQRS\Bus\Infrastructure\Event;

use Mediagone\CQRS\Bus\Domain\Event\Event;
use Mediagone\CQRS\Bus\Domain\Event\EventBus;
use Mediagone\CQRS\Bus\Domain\Event\EventListener;


final class EventBusDispatcher implements EventBus
{
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    /** @var EventListener[] */
    private array $listeners;
    
    
    
    //========================================================================================================
    // Constructor
    //========================================================================================================
    
    public function __construct(array $listeners)
    {
        $this->listeners = $this->checkListenerArray(...$listeners);
    }
    
    
    
    //========================================================================================================
    // Methods
    //========================================================================================================
    
    public function dispatch(Event $event) : void
    {
        foreach ($this->listeners as $listener) {
            if ($listener->supports($event)) {
                $listener->listen($event);
            }
        }
    }
    
    
    
    //========================================================================================================
    // Helpers
    //========================================================================================================
    
    private function checkListenerArray(EventListener ...$listeners) : array
    {
        return $listeners;
    }
    
    
    
}
