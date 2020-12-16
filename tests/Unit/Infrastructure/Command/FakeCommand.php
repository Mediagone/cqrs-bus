<?php declare(strict_types=1);

namespace Tests\Mediagone\CQRS\Bus\Infrastructure\Command;

use Mediagone\CQRS\Bus\Domain\Command\Command;


final class FakeCommand implements Command
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
