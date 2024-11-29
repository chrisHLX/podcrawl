<?php

namespace App\Http\Services;
use Illuminate\Support\Facades\Log;
use SpotifyWebAPI\SpotifyWebAPI;
use SpotifyWebAPI\Session;
use App\Models\PodcastEpisode; // Assuming a model for episodes exists
use App\Models\Show;

class SpotifyService
{
    protected $api;
    

    public function __construct()
    {
        $session = new Session(
            env('SPOTIFY_CLIENT_ID'), 
            env('SPOTIFY_CLIENT_SECRET')
        );

        $session->requestCredentialsToken();
        $accessToken = $session->getAccessToken();
        
        $this->api = new SpotifyWebAPI();
        $this->api->setAccessToken($accessToken);
    }

    public function getEpisodeData($episodeId)
    {
        // Fetch episode details from Spotify
        return $this->api->getEpisode($episodeId);
    }



    public function saveEpisodeToDatabase($episodeId)
    {
        // Check if the episode already exists in the database
        $existingEpisode = PodcastEpisode::where('spotify_id', $episodeId)->first();

        if ($existingEpisode) {
            return $existingEpisode; // Already exists, so return it
        }

        // Fetch episode data from Spotify
        // This search has additional information. 
        // The search itself doesn't return the show_name for example but if you search for an episode based on ID this information is returned
        $episodeData = $this->getEpisodeData($episodeId);
        
        // Create a new episode record
        return PodcastEpisode::create([
            'spotify_id' => $episodeData->id,
            'name' => $episodeData->name,
            'description' => $episodeData->description,
            'release_date' => $episodeData->release_date,
            'duration_ms' => $episodeData->duration_ms,
            'language' => $episodeData->language,
            'show_name' => $episodeData->show->name, // Show info if needed
            'image_url' => $episodeData->images[0]->url ?? null, // Use the first image
            'spotify_url' => $episodeData->external_urls->spotify
        ]);
    }

    public function searchEpisode($query)
    {
        $encodedQuery = urlencode($query); // Ensure proper URL encoding

        // Add 'market' parameter if required (e.g., 'AU' for Australia)
        $options = [
            'limit' => 50,
            'market' => 'AU'
        ];

        // Search for episodes
        $results = $this->api->search($encodedQuery, 'episode', $options);
        // \Log::info('Search results: ', ['results' => json_encode($results)]);

        // Check if episodes were returned
        if (!empty($results->episodes->items)) {
            return json_decode(json_encode($results->episodes->items), true); // Convert to array
        }

        // Check for additional pages of results
        if (isset($results->episodes->next)) {
            $options['offset'] = 10; // Set offset for the next page
            $nextPageResults = $this->api->search($encodedQuery, 'episode', $options);
            // \Log::info('Next page of results: ', ['results' => json_encode($nextPageResults)]);
            return json_decode(json_encode(array_merge($results->episodes->items, $nextPageResults->episodes->items)), true);
        }

        return null; // No episodes found
    }


    public function searchShow($showName, $returnFirstId = false)
    {
        $encodedQuery = urlencode($showName); // Ensure proper URL encoding

        // Add 'market' parameter if required (e.g., 'AU' for Australia)
        $options = [
            'limit' => 10,
            'market' => 'AU'
        ];

        // Search for shows
        $results = $this->api->search($encodedQuery, 'show', $options);
        \Log::info('Search results for show: ', ['results' => json_encode($results)]);

        // Check if shows were returned
        if (!empty($results->shows->items)) {
            // If $returnFirstId is true, return the Spotify ID of the first item
            if ($returnFirstId) {

                $newShow = $results->shows->items[0] ?? null;
                if($newShow)
                {
                    return Show::create([
                        'name' => $newShow->name,
                        'publisher' => $newShow->publisher,
                        'description' => $newShow->description,
                        'spotify_id' => $newShow->id,
                        'image_url' => $newShow->images[0]->url ?? null,
                        'spotify_url' => $newShow->external_urls->spotify
                    ]);
                }
                
            }

            // Otherwise, return a list of shows as an array
            return json_decode(json_encode($results->shows->items), true);
        }

        // Check for additional pages of results
        if (isset($results->shows->next)) {
            $options['offset'] = 10; // Set offset for the next page
            $nextPageResults = $this->api->search($encodedQuery, 'shows', $options);
            // \Log::info('Next page of results: ', ['results' => json_encode($nextPageResults)]);
            return json_decode(json_encode(array_merge($results->shows->items, $nextPageResults->shows->items)), true);
        }

        return null; // No shows found
    }


    //trialling this by copying the get episode function
    public function getShowData($showId)
    {
        // Check if the episode already exists in the database
        $existingShow = Show::where('spotify_id', $showId)->first();

        if ($existingShow) {
            return $existingShow; //if it exists return it
        }
        // if it doesnt - Fetch show details from Spotify and save it to the database then return it 
        $shows = $this->api->getShow($showId);
        

        return Show::create([
            'name' => $shows->name,
            'publisher' => $shows->publisher,
            'description' => $shows->description,
            'spotify_id' => $shows->id,
            'image_url' => $shows->images[0]->url ?? null,
            'spotify_url' => $shows->external_urls->spotify
        ]);

        
    }
    
    
   
    

}
