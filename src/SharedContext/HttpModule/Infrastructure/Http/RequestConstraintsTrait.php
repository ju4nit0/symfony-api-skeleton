<?php

declare(strict_types=1);

namespace App\SharedContext\HttpModule\Infrastructure\Http;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Mapping\ClassMetadata;

trait RequestConstraintsTrait
{
    /** @return array<string, Constraint[]> */
    abstract protected static function bodyConstraints(): array;

    public static function loadValidatorMetadata(ClassMetadata $metadata): void
    {
        $metadata->addGetterConstraint(
            'body',
            new Collection(
                static::bodyConstraints(),
                null,
                null,
                true,
                false,
            ),
        );
    }
}
