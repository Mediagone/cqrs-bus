<?php declare(strict_types=1);

namespace Mediagone\CQRS\Bus\Infrastructure\Event;

use Mediagone\CQRS\Bus\Domain\Event\Event;
use Mediagone\CQRS\Bus\Domain\Event\EventBus;
use Mediagone\CQRS\Bus\Infrastructure\Event\Utils\EventListenerProvider;


final class EventBusDispatcher implements EventBus
{
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    private EventListenerProvider $provider;
    
    
    
    //========================================================================================================
    // Constructor
    //========================================================================================================
    
    public function __construct(EventListenerProvider $listenerProvider)
    {
        $this->provider = $listenerProvider;
    }
    
    
    
    //========================================================================================================
    // Methods
    //========================================================================================================
    
    public function dispatch(Event $event) : void
    {
        foreach ($this->provider->getListeners() as $listener) {
            if ($listener->supports($event)) {
                $listener->listen($event);
            }
        }
    }
    
    
    
}
