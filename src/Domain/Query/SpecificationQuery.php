<?php declare(strict_types=1);

namespace Mediagone\CQRS\Bus\Domain\Query;

use Mediagone\Doctrine\Specifications\SpecificationCompound;


abstract class SpecificationQuery extends SpecificationCompound implements Query
{

}
