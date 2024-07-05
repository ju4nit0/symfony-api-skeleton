<?php

declare(strict_types=1);

namespace App\SharedContext\StatusModule\Infrastructure\Entrypoint\Http\Api\V1;

use App\SharedContext\HttpModule\Infrastructure\Http\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('health', methods: ['GET'])]
readonly class GetHealthController implements Controller
{
    public function __invoke(Request $request): Response
    {
        $status = [
            'status' => 'ok',
            'services' => [
            ]
        ];

        return new JsonResponse($status, 200, ['cache-control' => 'no-cache']);
    }
}
