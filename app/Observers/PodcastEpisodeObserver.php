<?php

namespace App\Observers;
use Illuminate\Support\Facades\Log;
use App\Models\PodcastEpisode;
use App\Models\Show;
use App\Models\People;
use App\Models\Episode_Embeddings;
use App\Http\Services\OpenAIService;
use App\Http\Services\SpotifyService;

class PodcastEpisodeObserver
{
    protected $openAIService;
    protected $spotifyService;

    public function __construct(OpenAIService $openAIService, SpotifyService $spotifyService)
    {
        $this->openAIService = $openAIService;
        $this->spotifyService = $spotifyService;
    }

    public function created(PodcastEpisode $episode)
    {
        
        
        Log::info('a podcastepisode was created: {id}', ['id' => $episode->id]);
        //Goal: when a episode is created the host is updated by matching the show name and the publisher as the host
        $show = Show::where('name', $episode->show_name)->first();

        /* ------- Generate Embedding for each episode ------- */
        $vector = $this->openAIService->getEmbedding($episode->name, $episode->description, $episode->show_name);
        Log::info('this is the VECTOR: {vector}', ['vector' => $vector]);

        $encVector = json_encode($vector);
        if ($encVector) {
            Log::info('IF STATEMENT IS YES FOR VECTOR');
            Log::info('this is the VECTOR: {vector}', ['vector' => $vector]);
            Episode_Embeddings::Create([
                'name' => $episode->name,
                'spotify_id' => $episode->spotify_id,
                'vector' => $encVector
            ]);
        } else {
            Log::info('IF STATEMENT IS NO FOR VECTOR'); 
        }


        // so when a show is now created if the show doesnt exist it will create it and then with the show observer
        // it will trigger the show created event and the host id and people id will be attached.
        if ($show) 
        {
            // Show exists, now you can access properties like $show->id
            // need to check if host_id exists 
            if ($show->host_id){
                Log::info('found show: {sname}', ['sname' => $show->name]);
                Log::info('show host id: {shid}', ['shid' => $show->host_id]);
                // Attach the person as the host
                $episode->people()->attach($show->host_id, ['role' => 'host']);
                $episode->save();
            }
        } else {
            echo 'No show in database';
            Log::info('found no show.. '); // so lets find and add one
            $show = $this->spotifyService->searchShow($episode->show_name, true); //with the condition true this retrieves the podcast id which we can then use to create the show
            Log::info('the show was created:', ['show name' => $show->name]);
            
            Log::info('attempting to update the pivot table....... ');
            $episode->people()->attach($show->host_id, ['role' => 'host']);
            $episode->save();
        }

        Log::info('attempting to linkn the guest in pivot table....... ');
        // Link the guest in pivot table
        $guest = $this->openAIService->getPeopleData($episode->name, $episode->show_name, 'no info on publisher but its probably in the show name', 'guest');
        Log::info('Chat GPT Response', [
            'Guest' => [
                'Name' => $guest['guest']['name'] ?? 'Unknown',
                'DOB' => $guest['guest']['DOB'] ?? 'Not provided',
                'Podcast Name' => $guest['guest']['rss'] ?? 'Not provided',
                'Interests' => $guest['guest']['interests'] ?? 'Not provided',
                'Description' => $guest['guest']['description'] ?? 'Not provided',
                'Aliases' => $guest['guest']['aliases'] ?? 'Not provided'
            ],
        ]);

        $nameToSearch = $guest['guest']['name'];
        if (People::where('name', $nameToSearch)->first())
        {
            Log::info('found the name in the database');
        }
        else
        {
            Log::info('didnt find the name in the database');
        }
        
    }



    /**
     * Extract host name from the description.
     */
    private function extractHostName(string $description): ?string
    {
        // Implement logic to extract the host's name from the description
        // For example, use a regex or AI model to analyze the description
        return null; // Placeholder
    }

    /**
     * Extract guest names from the description.
     */
    private function extractGuestNames(string $description): array
    {
        // Implement logic to extract guest names from the description
        // For example, use a regex or AI model to analyze the description
        return []; // Placeholder
    }
}

