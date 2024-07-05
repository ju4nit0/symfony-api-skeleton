<?php

declare(strict_types=1);

namespace App\SharedContext\SharedModule\Domain\ValueObject;

use App\SharedContext\SharedModule\Domain\Contract\Id;
use App\SharedContext\SharedModule\Domain\Exception\InvalidUuidException;
use Ramsey\Uuid\Uuid as RamseyUuid;

readonly class Uuid extends StringValueObject implements Id
{
    final public function __construct(string $value = null)
    {
        parent::__construct($value ?? self::v7()->value);
    }

    protected static function exceptionClass(): ?string
    {
        return InvalidUuidException::class;
    }

    protected function doValidate(): void
    {
        parent::doValidate();
        if (
            RamseyUuid::NIL === $this->value
            || RamseyUuid::MAX === $this->value
            || !RamseyUuid::isValid($this->value)
        ) {
            throw InvalidUuidException::byValue($this->value);
        }
    }

    public function version(): int
    {
        /** @var \Ramsey\Uuid\Rfc4122\FieldsInterface $fields */
        $fields = RamseyUuid::fromString($this->value)->getFields();
        return $fields->getVersion() ?? 0;
    }

    public static function v4(): static
    {
        return new static(RamseyUuid::uuid4()->toString());
    }

    public static function v7(): static
    {
        return new static(RamseyUuid::uuid7()->toString());
    }

    public static function fixedLength(): ?int
    {
        return 36;
    }
}
