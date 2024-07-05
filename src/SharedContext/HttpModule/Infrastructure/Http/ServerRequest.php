<?php

declare(strict_types=1);

namespace App\SharedContext\HttpModule\Infrastructure\Http;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Mapping\ClassMetadata;

abstract class ServerRequest
{
    final public function __construct(
        private readonly RequestStack $requestStack,
    ) {
    }

    final public function sfRequest(): Request
    {
        return $this->requestStack->getCurrentRequest(); // @phpstan-ignore-line
    }

    final protected function getBody(): array // @phpstan-ignore-line
    {
        return $this->sfRequest()->request->all();
    }

    final protected function getQuery(): array // @phpstan-ignore-line
    {
        return $this->sfRequest()->query->all();
    }

    final protected function getContent(): string
    {
        return $this->sfRequest()->getContent();
    }

    final protected function getFiles(): array // @phpstan-ignore-line
    {
        return $this->sfRequest()->files->all();
    }

    final protected function getAttributes(): array // @phpstan-ignore-line
    {
        return $this->sfRequest()->attributes->all();
    }

    /** Description: To validate use RequestConstraintsTrait and rewrite method bodyConstraints */
    abstract public static function loadValidatorMetadata(ClassMetadata $metadata): void;
}
