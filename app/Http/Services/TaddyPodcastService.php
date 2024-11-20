<?php

namespace App\Http\Services;

use GuzzleHttp\Client;

class TaddyPodcastService
{
    protected $client;
    protected $apiKey;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://api.taddypodcast.com/', // Base URL for Taddy Podcast API
        ]);

        $this->apiKey = env('33d1b524fb55c742132dded1d95741efdbe6c52201004194dc140cb068afbc9578dee40953d177735b0349d5dfca576419

'); // API key stored in .env
    }

    // Fetch episodes of a podcast by ID
    public function getPodcastEpisodes($podcastId)
    {
        try {
            $response = $this->client->request('GET', "podcasts/$podcastId/episodes", [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey,
                ],
            ]);

            return json_decode($response->getBody()->getContents(), true);

        } catch (\Exception $e) {
            return ['error' => 'Unable to fetch podcast episodes.'];
        }
    }

    // Fetch a specific episode by ID
    public function getEpisode($episodeId)
    {
        try {
            $response = $this->client->request('GET', "episodes/$episodeId", [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey,
                ],
            ]);

            return json_decode($response->getBody()->getContents(), true);

        } catch (\Exception $e) {
            return ['error' => 'Unable to fetch episode.'];
        }
    }

    //Function to search for a podcast id by name
    public function searchPodcastByName($podcastName)
    {
        try {
            $response = $this->client->request('GET', 'search', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey,
                ],
                'query' => [
                    'q' => $podcastName,
                ],
            ]);

            return json_decode($response->getBody()->getContents(), true);

        } catch (\Exception $e) {
            return ['error' => 'Unable to search for podcast.'];
        }
    }

}
