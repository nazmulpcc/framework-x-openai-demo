<?php

namespace App;

use Clue\React\EventSource\MessageEvent;
use React\Http\Browser;
use React\Stream\ThroughStream;

final class OpenAi
{
    protected string $api_key;

    public function __construct(?string $api_key = null)
    {
        $this->api_key = $api_key ?? $_ENV['OPENAI_API_KEY'];
    }

    public function completion(string $prompt, bool $strem = true, string $model = 'gpt-3.5-turbo')
    {
        $sink = $this->getSinkStream();

        $request = (new Browser())
            ->withHeader('Authorization', 'Bearer ' . $this->api_key)
            ->withHeader('Content-Type', 'application/json')
            ->requestStreaming('POST', 'https://api.openai.com/v1/chat/completions', body: json_encode([
                'model' => $model,
                'messages' => [[
                    'role'    => 'user',
                    'content' => $prompt
                ]],
                'stream'   => $strem
            ]));

        $request->then(fn ($response) => $response->getBody()->pipe($sink));

        return $sink;
    }

    protected function getSinkStream(): ThroughStream
    {
        return new ThroughStream(function ($data) {
            $message = MessageEvent::parse($data, '');
            $data = json_decode($message->data, true);

            if (!isset($data['choices'])) {
                return "data: " . json_encode([
                    'content' => null
                ]) . "\n\n";
            }

            return "data: " . json_encode([
                'content' => $data['choices'][0]['delta']['content'] ?? null,
            ]) . "\n\n";
        });
    }
}
