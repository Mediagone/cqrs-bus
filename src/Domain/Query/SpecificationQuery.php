<?php declare(strict_types=1);

namespace Mediagone\CQRS\Bus\Domain\Query;

use Mediagone\Doctrine\Specifications\SpecificationCollection;


abstract class SpecificationQuery extends SpecificationCollection implements Query
{

}
