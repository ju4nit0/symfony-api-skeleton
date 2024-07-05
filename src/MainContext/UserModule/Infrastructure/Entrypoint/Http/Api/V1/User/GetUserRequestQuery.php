<?php

declare(strict_types=1);

namespace App\MainContext\UserModule\Infrastructure\Entrypoint\Http\Api\V1\User;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class GetUserRequestQuery
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(exactly: 36)]
        public string $id
    ) {
    }
}
