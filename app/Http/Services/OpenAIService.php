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
    public function callOpenAI($prompt, $tokens = 300)
    {
        Log::info('prompt', ['prompt' => $prompt]);
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
                'max_tokens' => $tokens,
            ],
        ])->getBody();
    }

    // create a function for getting information on the person/people in a podcast episode could get guest and people info for both guest and host
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

        return $this->handlePeopleData($prompt, $infoType);
    }

    public function handlePeopleData($prompt, $infoType)
    {

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

        
    public function trimDescription($description, $wordLimit)
    {
        // Split the description into an array of words
        $words = explode(' ', $description);
        
        // Slice the array to get the first $wordLimit words
        $trimmedWords = array_slice($words, 0, $wordLimit);
        
        // Join the words back into a string
        return implode(' ', $trimmedWords);
    }

    public function embeddingRequest($data)
    {
        return $this->client->post('https://api.openai.com/v1/embeddings', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'model' => 'text-embedding-3-small', // Use the embeddings model
                'input' => $data, // The input text to generate embeddings for
            ],
        ])->getBody();
    }

    public function getEmbedding($name, $description, $show_name)
    {   
        //normalize data to embedding 
        $tW = str_word_count($name);
        $sW = str_word_count($show_name);
        $wordLimit = 65 - $tW - $sW;
        $trimDes = $this->trimDescription($description, $wordLimit);
        // Combine all the relevant information into a single text string.

        $text = "Episode Title: $name 
        Podcast Show: $show_name
        Episode Description: $trimDes";
        
        // Request to OpenAI's embedding API
        $response = $this->embeddingRequest($text);

            // Decode the JSON response to an array
        $responseBody = json_decode($response, true);

        // Return the embedding from the response
        return $responseBody['data'][0]['embedding'] ?? null; // Using null coalescing to avoid error if data is missing
    }

    public function searchEmbedding($query)
    {
        $response = $this->embeddingRequest($query);
        // Decode the JSON response to an array
        $responseBody = json_decode($response, true);
        // Return the embedding from the response
        return $responseBody['data'][0]['embedding'] ?? null; // Using null coalescing to avoid error if data is missing
    }

    public function cosineSimilarity($vectorA, $vectorB) {
        $normalizedA = $this->normalizeVector($vectorA);
        $normalizedB = $this->normalizeVector($vectorB);
        $dotProduct = array_sum(array_map(fn($a, $b) => $a * $b, $normalizedA, $normalizedB));
        return $dotProduct; // Since both are unit vectors, no need for magnitude division
    }
    
    public function normalizeVector(array $vector): array {
        $magnitude = sqrt(array_sum(array_map(fn($x) => $x ** 2, $vector)));
        return array_map(fn($x) => $x / $magnitude, $vector);
    }

    public function wordCheck($word) {
        $prompt = "
                Check if the following word is a swear word or explicit. If it is, return FALSE. If it is not explicit and the word is spelled incorrectly, correct it. Return the corrected word if applicable. If no correction is possible, return FALSE. 
                WORD TO CHECK: $word
                NOTE: Your response can only be either FALSE or the corrected word.
            ";
            
        $response = $this->callOpenAI($prompt);

        $data = json_decode($response, true);

        // Parse response
        $nWord = $data['choices'][0]['message']['content'];

        return $nWord;
    }
    
    
    
}
