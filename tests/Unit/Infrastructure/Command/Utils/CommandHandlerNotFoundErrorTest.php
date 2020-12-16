<?php declare(strict_types=1);

namespace Tests\Mediagone\CQRS\Bus\Infrastructure\Command\Utils;

use Mediagone\CQRS\Bus\Infrastructure\Command\Utils\CommandHandlerNotFoundError;
use PHPUnit\Framework\TestCase;
use Tests\Mediagone\CQRS\Bus\Infrastructure\Command\FakeCommand;


/**
 * @covers \Mediagone\CQRS\Bus\Infrastructure\Command\Utils\CommandHandlerNotFoundError
 */
final class CommandHandlerNotFoundErrorTest extends TestCase
{
    //========================================================================================================
    // Tests
    //========================================================================================================
    
    public function test_can_be_created() : void
    {
        $command = new FakeCommand();
        
        self::assertInstanceOf(CommandHandlerNotFoundError::class, new CommandHandlerNotFoundError($command));
    }
    
    
    
}
