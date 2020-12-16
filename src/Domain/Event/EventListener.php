<?php declare(strict_types=1);

namespace Mediagone\CQRS\Bus\Domain\Event;


interface EventListener
{
    public function supports(Event $event) : bool;
    public function listen(Event $event) : void;
}
