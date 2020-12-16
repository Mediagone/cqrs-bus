<?php declare(strict_types=1);

namespace Tests\Mediagone\CQRS\Bus\Infrastructure\Event;

use Mediagone\CQRS\Bus\Domain\Event\Event;


final class FakeEvent implements Event
{
    private $callback;
    
    
    public function __construct(?callable $callback = null)
    {
        $this->callback = $callback;
    }
    
    
    public function __invoke()
    {
        if ($this->callback !== null) {
            ($this->callback)();
        }
    }
    
    
    
}
