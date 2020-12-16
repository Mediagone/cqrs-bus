<?php declare(strict_types=1);

namespace Mediagone\CQRS\Bus\Infrastructure\Command;

use InvalidArgumentException;
use Mediagone\CQRS\Bus\Domain\Command\Command;
use Mediagone\CQRS\Bus\Domain\Command\CommandBus;
use Mediagone\CQRS\Bus\Infrastructure\Command\Utils\CommandHandlerNotFoundError;
use Symfony\Component\DependencyInjection\ServiceLocator;
use function get_class;
use function trim;


/**
 * Finds and executes the matching CommandHandler to handle the Command.
 */
final class CommandBusSuffixResolver implements CommandBus
{
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    private ServiceLocator $serviceLocator;
    
    private ?CommandBus $next;
    
    private string $suffix;
    
    
    
    //========================================================================================================
    // Constructor
    //========================================================================================================
    
    public function __construct(ServiceLocator $serviceLocator, string $suffix, ?CommandBus $next)
    {
        $this->serviceLocator = $serviceLocator;
        $this->suffix = trim($suffix);
        $this->next = $next;
    
        if ($this->suffix === '') {
            throw new InvalidArgumentException('Command handler suffix must be specified.');
        }
    }
    
    
    
    //========================================================================================================
    // Methods
    //========================================================================================================
    
    /**
     * Routes a command to its handler or passes it to the inner bus (if defined).
     *
     * @throws CommandHandlerNotFoundError Thrown if no handler is found for the command.
     */
    public function do(Command $command) : void
    {
        $handlerClassName = get_class($command).$this->suffix;
        
        if ($this->serviceLocator->has($handlerClassName)) {
            $handler = $this->serviceLocator->get($handlerClassName);
            $handler->handle($command);
            return;
        }
        
        if ($this->next === null) {
            throw new CommandHandlerNotFoundError($command);
        }
        
        $this->next->do($command);
    }
    
    
    
}
