<?php declare(strict_types=1);

namespace Mediagone\CQRS\Bus\Infrastructure\Command\Utils;

use LogicException;
use Mediagone\CQRS\Bus\Domain\Command\Command;
use function get_class;


final class CommandHandlerNotFoundError extends LogicException
{
    //========================================================================================================
    // Constructor
    //========================================================================================================
    
    public function __construct(Command $command)
    {
        parent::__construct('Handler class not found for the "' . get_class($command) . '" command. Is it registered and tagged accordingly in the services.yaml file?');
    }
    
    
    
}
