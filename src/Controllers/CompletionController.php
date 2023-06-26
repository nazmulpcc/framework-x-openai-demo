<?php

namespace App\Controllers;

use App\OpenAi;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;

class CompletionController
{
	public function __invoke(ServerRequestInterface $request)
	{
		$data = json_decode($request->getBody()->getContents(), true);
		if (!$prompt = $data['prompt'] ?? false) {
			return new Response(422, ['Content-Type' => 'application/json'], '{}');
		}

		$prompt = "Write an article based on the following direction:
		{$prompt}
		The article must have proper html tags as needed and it should open and close with a div tag.";

		$response = (new OpenAi)->completion($prompt);

		return new Response(200, ['Content-Type' => 'text/event-stream'], $response);
	}
}
