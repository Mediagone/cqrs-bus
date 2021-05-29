<?php declare(strict_types=1);

namespace Mediagone\CQRS\Bus\Domain\Event;


interface EventBus
{
    /**
     * Routes an event to all registered listeners.
     */
    public function dispatch(Event $event) : void;
}
