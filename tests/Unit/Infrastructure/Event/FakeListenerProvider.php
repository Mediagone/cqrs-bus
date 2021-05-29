<?php declare(strict_types=1);

namespace Tests\Mediagone\CQRS\Bus\Infrastructure\Event;

use Mediagone\CQRS\Bus\Infrastructure\Event\Utils\EventListenerProvider;


final class FakeListenerProvider implements EventListenerProvider
{
    private array $listeners;
    
    public function __construct(array $listeners)
    {
        $this->listeners = $listeners;
    }
    
    public function getListeners() : array
    {
        return $this->listeners;
    }
}
