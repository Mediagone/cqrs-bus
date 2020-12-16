<?php declare(strict_types=1);

namespace Mediagone\CQRS\Bus\Infrastructure\Command;

use Exception;
use Mediagone\CQRS\Bus\Domain\Command\Command;
use Mediagone\CQRS\Bus\Domain\Command\CommandBus;


/**
 * Forces sub-commands to execute one after the other to preserve atomicity and the order of commands.
 */
final class CommandBusQueue implements CommandBus
{
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    private CommandBus $innerBus;
    
    /**
     * @var Command[]
     */
    private array $commandQueue = [];
    
    private bool $isHandling = false;
    
    
    
    //========================================================================================================
    // Constructor
    //========================================================================================================
    
    /**
     * @param CommandBus $innerBus A nested wrapper-bus to define a custom behavior or execute the command.
     */
    public function __construct(CommandBus $innerBus)
    {
        $this->innerBus = $innerBus;
    }
    
    
    
    //========================================================================================================
    // Methods
    //========================================================================================================
    
    /**
     * @throws Exception
     */
    public function do(Command $command) : void
    {
        $this->commandQueue[] = $command;
        
        if ($this->isHandling) {
            return;
        }
        
        try {
            $this->isHandling = true;
            
            while ($command = array_shift($this->commandQueue)) {
                $this->innerBus->do($command);
            }
        }
        catch (Exception $exception) {
            $this->skipRemainingCommands();
            
            throw $exception;
        }
        finally {
            $this->isHandling = false;
        }
        
    }
    
    
    
    //========================================================================================================
    // Helpers
    //========================================================================================================
    
    private function skipRemainingCommands() : void
    {
        $this->commandQueue = [];
    }
    
    
    
}
