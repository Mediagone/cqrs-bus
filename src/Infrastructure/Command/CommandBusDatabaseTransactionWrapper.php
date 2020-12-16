<?php declare(strict_types=1);

namespace Mediagone\CQRS\Bus\Infrastructure\Command;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Mediagone\CQRS\Bus\Domain\Command\Command;
use Mediagone\CQRS\Bus\Domain\Command\CommandBus;


/**
 * Wraps the Command inside a database transaction, with automatic rollback on failure.
 */
final class CommandBusDatabaseTransactionWrapper implements CommandBus
{
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    private CommandBus $innerBus;
    
    private ManagerRegistry $managerRegistry;
    
    
    
    //========================================================================================================
    // Constructor
    //========================================================================================================
    
    public function __construct(CommandBus $innerBus, ManagerRegistry $managerRegistry)
    {
        $this->innerBus = $innerBus;
        $this->managerRegistry = $managerRegistry;
    }
    
    
    
    //========================================================================================================
    // Methods
    //========================================================================================================
    
    public function do(Command $command) : void
    {
        /** @var EntityManagerInterface $manager */
        $manager = $this->managerRegistry->getManager();
        
        /** @var Connection $connection */
        $connection = $this->managerRegistry->getConnection();
        
        $connection->beginTransaction();
        
        try {
            $this->innerBus->do($command);
            
            $manager->flush();
            $connection->commit();
        }
        catch (Exception $exception) {
            $connection->rollBack();
            
            throw $exception;
        }
    }
    
    
    
}
