<?php

namespace App\Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class CorsMiddleware extends Middleware
{
    public function __invoke(ServerRequestInterface $request, callable $next)
    {
        /** @var ResponseInterface $response */
        $response = $next($request);

        if (!($response instanceof ResponseInterface)) {
            return $response;
        }

        return $response
            ->withHeader('Access-Control-Allow-Origin', $request->getHeader('Origin')[0] ?? '*')
            ->withHeader('Access-Control-Allow-Headers', 'Authorization, Content-Type, x-wp-nonce, x-requested-with')
            ->withHeader('Access-Control-Allow-Methods', 'GET, OPTIONS')
            ->withHeader('Access-Control-Allow-Credentials', 'true');
    }
}
