<?php declare(strict_types=1);

namespace Mediagone\CQRS\Bus\Infrastructure\Command;

use Exception;
use Mediagone\CQRS\Bus\Domain\Command\Command;
use Mediagone\CQRS\Bus\Domain\Command\CommandBus;
use Mediagone\CQRS\Bus\Infrastructure\Event\EventBusQueue;


final class CommandBusEventCollector implements CommandBus
{
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    private CommandBus $innerBus;

    private EventBusQueue $eventCollector;
    
    
    
    //========================================================================================================
    // Constructor
    //========================================================================================================
    
    /**
     * @param CommandBus $innerBus A nested wrapper-bus to define a custom behavior or execute the command.
     */
    public function __construct(CommandBus $innerBus, EventBusQueue $eventBus)
    {
        $this->innerBus = $innerBus;
        $this->eventCollector = $eventBus;
    }
    
    
    
    //========================================================================================================
    // Methods
    //========================================================================================================
    
    /**
     * @throws Exception Any exception thrown in the command.
     */
    public function do(Command $command) : void
    {
        try{
            $this->eventCollector->startCollecting();
            $this->innerBus->do($command);
            $this->eventCollector->releaseCollected();
        }
        catch (Exception $exception) {
            // If the command fails, we don't want collected events to be dispatched.
            $this->eventCollector->discardCollected();
            
            throw $exception;
        }
    }
    
    
    
}
