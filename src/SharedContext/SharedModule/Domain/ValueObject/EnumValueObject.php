<?php

declare(strict_types=1);

namespace App\SharedContext\SharedModule\Domain\ValueObject;

use ApiRa\SharedContext\SharedModule\Domain\Exception\InvalidEnumException;
use ApiRa\SharedContext\SharedModule\Domain\Utils\StringUtils;
use ApiRa\SharedContext\SharedModule\Domain\ValueObject\StringValueObject;
use BadMethodCallException;
use ReflectionClass;

readonly abstract class EnumValueObject extends StringValueObject
{
    protected function doValidate(): void
    {
        parent::doValidate();

        $constants = (new ReflectionClass(static::class))->getConstants();

        if (!in_array($this->value, $constants, true)) {
            throw InvalidEnumException::notValidValue($this->value, $constants);
        }
    }

    private function isValidCheckFunction(string $functionName): bool
    {
        return (bool)preg_match('/is([A-Z].*)/', $functionName, $outputArray);
    }

    private function checkValue(string $name): bool
    {
        $constants = (new ReflectionClass(static::class))->getConstants();

        foreach ($constants as $constantKey => $constant) {
            if ($name === 'is' . ucfirst(StringUtils::toCamelCase(strtolower($constantKey)))) {
                return $this->value === $constant;
            }
        }

        throw new BadMethodCallException(
            sprintf(
                '%s is not a valid function because it has not an associated with any valid enum value: [%s]',
                $name,
                implode(',', $constants),
            ),
        );
    }

    /**
     * @param array<mixed> $arguments
     */
    public function __call(string $name, array $arguments): mixed
    {
        if ($this->isValidCheckFunction($name)) {
            return $this->checkValue($name);
        }

        throw new BadMethodCallException();
    }

    /**
     * @param array<mixed> $arguments
     */
    public static function __callStatic(string $name, array $arguments): static
    {
        $constants = (new ReflectionClass(static::class))->getConstants();

        $name = strtoupper($name);
        if (array_key_exists($name, $constants)) {
            return new static($constants[$name]); // @phpstan-ignore-line
        }

        throw new BadMethodCallException(
            sprintf(
                '%s is not a valid static function because it has not an associated with any valid enum value: [%s]',
                $name,
                implode(',', $constants),
            ),
        );
    }
}
