<?php

namespace App\Http\Services;
use Illuminate\Support\Facades\Log;

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


    //call to the open ai api
    public function callOpenAI($prompt)
    {
        return $this->client->post('https://api.openai.com/v1/chat/completions', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $prompt,
                    ],
                ],
                'max_tokens' => 300,
            ],
        ])->getBody();
    }

    //create a function for getting information on the person/people in a podcast episode could get guest and people info for both guest and host
    // you could pass the publisher, podcast name and episode title.

    public function getPeopleData($episodeName, $showName, $showPublisher, $infoType) //input type is either host or guest
    {
        $prompt = "Based on the following information about the podcast:
        - Episode Name: $episodeName
        - Show Name/Podcast Name: $showName
        - Show/Podcast Publisher: $showPublisher    

        Provide detailed information about the $infoType in the following JSON format:
        {
            \"$infoType\": {
                \"name\": \"\",
                \"date_of_birth\": \"\",
                \"podcast_name\": \"\",
                \"interests\": \"\",
                \"description\": \"\",
                \"aliases\": \"\"
            }
        }
        If any information is not available, use unavailable for the value. 
        please do your best to get information for date of birth use the format year-month-date e.g 1990-12-25
        provide the most canonical(the most obvious and popular name of the person) name and any other common aliases add to the aliases field (an array of alternate names).
        ";

        $response = $this->callOpenAI($prompt);
        $response = json_decode($response, true);

        Log::info('Chat GPT raw response', ['response' => $response]);

        $content = $response['choices'][0]['message']['content'] ?? null;

        if ($content) {
            try {
                // Decode JSON content returned by GPT
                $data = json_decode($content, true);

                if (json_last_error() === JSON_ERROR_NONE) {
                    // Map data to expected structure
                    $mappedData = [
                        $infoType => [
                            'name' => $data[$infoType]['name'] ?? 'Unknown',
                            'DOB' => $data[$infoType]['date_of_birth'] ?? null,
                            'rss' => $data[$infoType]['podcast_name'] ?? null,
                            'interests' => $data[$infoType]['interests'] ?? 'General',
                            'description' => $data[$infoType]['description'] ?? 'No description available',
                            'aliases' => $data[$infoType]['aliases'] ?? null
                        ]
                    ];

                    return $mappedData;
                } else {
                    throw new \Exception('Invalid JSON structure in GPT response');
                }
            } catch (\Exception $e) {
                Log::error('Failed to parse GPT response as JSON', ['error' => $e->getMessage()]);
                return "NO DATA SOMETHING WENT HORRIBLY WRONG!";
            }
        }

        return "NO DATA SOMETHING WENT HORRIBLY WRONG!";
    }

        
    public function trimDescription($description, $wordLimit = 20)
    {
        // Split the description into an array of words
        $words = explode(' ', $description);
        
        // Slice the array to get the first $wordLimit words
        $trimmedWords = array_slice($words, 0, $wordLimit);
        
        // Join the words back into a string
        return implode(' ', $trimmedWords);
    }

    public function getEmbedding($name, $description, $show_name)
    {   
        $trimDes = $this->trimDescription($description);
        // Combine all the relevant information into a single text string.
        $text = "Episode Name: $name 
        Episode Description: $description 
        Podcast Show Name(usually this is the host): $show_name";
    
        // Request to OpenAI's embedding API
        $response = $this->client->post('https://api.openai.com/v1/embeddings', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'model' => 'text-embedding-3-small', // Use the embeddings model
                'input' => $text, // The input text to generate embeddings for
            ],
        ]);

            // Decode the JSON response to an array
        $responseBody = json_decode($response->getBody(), true);

        // Return the embedding from the response
        return $responseBody['data'][0]['embedding'] ?? null; // Using null coalescing to avoid error if data is missing
    }


    
}
