<?php declare(strict_types=1);

namespace Mediagone\CQRS\Bus\Domain\Command;


interface CommandBus
{
    public function do(Command $command) : void;
}
