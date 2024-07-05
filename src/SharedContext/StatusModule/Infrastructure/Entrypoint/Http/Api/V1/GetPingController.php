<?php

declare(strict_types=1);

namespace App\SharedContext\StatusModule\Infrastructure\Entrypoint\Http\Api\V1;

use App\SharedContext\HttpModule\Infrastructure\Http\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('ping', methods: ['GET'])]
readonly class GetPingController implements Controller
{
    public function __invoke(Request $request): Response
    {
        return new Response('PONG', 200);
    }
}
