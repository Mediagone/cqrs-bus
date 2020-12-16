<?php declare(strict_types=1);

namespace Mediagone\CQRS\Bus\Domain\Command;


interface CommandHandler
{
    public function handle(Command $command) : void;
}
