<?php declare(strict_types=1);

namespace Tests\Mediagone\CQRS\Bus\Infrastructure\Command;

use Mediagone\CQRS\Bus\Domain\Command\Command;
use Mediagone\CQRS\Bus\Domain\Command\CommandHandler;


final class FakeCommand_Handler implements CommandHandler
{
    private array $handleList = [];
    
    
    /**
     * @param FakeCommand $command
     */
    public function handle(Command $command) : void
    {
        $this->handleList[] = $command;
        $command();
    }
    
    
    public function getHandledCommands() : array
    {
        return $this->handleList;
    }
    
    
    
}
