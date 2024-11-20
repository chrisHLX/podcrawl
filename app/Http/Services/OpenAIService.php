<?php

namespace App\Http\Services;

use GuzzleHttp\Client;

class OpenAIService
{
    protected $client;
    protected $apiKey;

    public function __construct()
    {
        // Specify the path to the CA certificate file this is for the SSL
        $caCertPath = storage_path('cacert.pem');
        $this->client = new Client([
            'verify' => $caCertPath, // Use the CA certificate to verify SSL
        ]);
        $this->apiKey = env('OPENAI_API_KEY'); // Make sure this is set in your .env file
    }

    public function summarizeText($text)
    {
        $response = $this->client->post('https://api.openai.com/v1/chat/completions', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => "Summarize the following text to approximately 20 words: $text",
                    ],
                ],
                'max_tokens' => 50, // You can adjust this as needed
            ],
        ]);

        return json_decode($response->getBody(), true);
    }

    public function generateTips($classW, $matches)
    {
        $response = $this->client->post('https://api.openai.com/v1/chat/completions', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => "Can you make recommendations on how to play: $classW in the war within, world of warcraft considering this match history: $matches",
                    ],
                ],
                'max_tokens' => 500, // You can adjust this as needed
            ],
        ]);

        return json_decode($response->getBody(), true);
    }
}
