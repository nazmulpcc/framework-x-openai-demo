<?php

namespace App\Middlewares;

use Psr\Http\Message\ServerRequestInterface;

abstract class Middleware
{
    abstract public function __invoke(ServerRequestInterface $request, callable $next);
}
