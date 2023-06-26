<?php

use App\Controllers\CompletionController;
use App\Middlewares\CorsMiddleware;
use Dotenv\Dotenv;
use React\Http\Message\Response;

require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();
$dotenv->required('OPENAI_API_KEY');

$app = new FrameworkX\App();

$app->post('/', CorsMiddleware::class, CompletionController::class);
$app->options('/', CorsMiddleware::class, fn () => new Response(200));

$app->run();
