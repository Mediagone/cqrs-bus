<?php declare(strict_types=1);

namespace Mediagone\CQRS\Bus\Infrastructure\Event;

use Mediagone\CQRS\Bus\Infrastructure\Event\Utils\EventListenerProvider;
use function iterator_to_array;


final class ServiceEventListenerIterableProvider implements EventListenerProvider
{
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    private iterable $listeners;
    
    
    
    //========================================================================================================
    // Constructor
    //========================================================================================================
    
    public function __construct(iterable $listeners)
    {
        $this->listeners = $listeners;
    }
    
    
    
    //========================================================================================================
    // Helpers
    //========================================================================================================
    
    public function getListeners() : array
    {
        return iterator_to_array($this->listeners);
    }
    
    
    
}
