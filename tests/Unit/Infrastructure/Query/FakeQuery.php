<?php declare(strict_types=1);

namespace Tests\Mediagone\CQRS\Bus\Infrastructure\Query;

use Mediagone\CQRS\Bus\Domain\Query\Query;


final class FakeQuery implements Query
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
