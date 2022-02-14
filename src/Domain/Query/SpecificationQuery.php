<?php declare(strict_types=1);

namespace Mediagone\CQRS\Bus\Domain\Query;

use Mediagone\Doctrine\Specifications\SpecificationCompound;

/**
 * @template T
 * @implements Query<T>
 */
abstract class SpecificationQuery extends SpecificationCompound implements Query
{
}
