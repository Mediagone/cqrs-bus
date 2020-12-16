<?php declare(strict_types=1);

namespace Tests\Mediagone\CQRS\Bus\Infrastructure\Event;

use Mediagone\CQRS\Bus\Domain\Event\Event;
use Mediagone\CQRS\Bus\Domain\Event\EventListener;


final class TestEventListener implements EventListener
{
    private $handleList = [];
    
    
    /**
     * @param TestEvent $event
     */
    public function listen(Event $event) : void
    {
        $this->handleList[] = $event;
        $event();
    }
    
    
    public function getListenedEvents() : array
    {
        return $this->handleList;
    }
    
    
    public function supports(Event $event) : bool
    {
        return true;
    }
    
    
    
}
