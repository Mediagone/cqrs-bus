<?php declare(strict_types=1);

namespace Mediagone\CQRS\Bus\Domain\Query;


/**
 * @template T
 */
interface Query
{
    // The Query interface implements no method definition because it is just a PHP object that hold data.
    // However, using an interface makes the code more readable, type safe and open to extension.
}
