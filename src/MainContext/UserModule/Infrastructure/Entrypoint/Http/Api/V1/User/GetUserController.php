<?php

declare(strict_types=1);

namespace App\MainContext\UserModule\Infrastructure\Entrypoint\Http\Api\V1\User;

use App\SharedContext\SharedModule\Infrastructure\Http\Server\ControllerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Annotation\Route;

final class GetUserController implements ControllerInterface
{
    public function __construct()
    {
    }

    #[Route('/user', name: 'get_user', methods: ['GET'])]
    public function __invoke(
        #[MapQueryString] GetUserRequestQuery $payload
    ): Response {

        return new JsonResponse([
            'id' => $payload->id,
            'name' => 'John Doe',
            'email' => 'mail@mail.com'
        ]);
    }
}
