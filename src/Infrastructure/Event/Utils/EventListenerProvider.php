<?php declare(strict_types=1);

namespace Mediagone\CQRS\Bus\Infrastructure\Event\Utils;

interface EventListenerProvider
{
    public function getListeners() : array;
}
