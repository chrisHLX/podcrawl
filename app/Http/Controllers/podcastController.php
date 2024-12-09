<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\PodCasts;
use App\Models\PodcastEpisode;
use App\Models\EpisodeEmbedding2;
use App\Models\UserSearch;
use App\Http\Services\OpenAIService;
use App\Http\Services\SpotifyService;
use Illuminate\Support\Facades\Cache;

class podcastController extends Controller
{
    protected $openAIService;
    protected $spotifyService;

    public function __construct(OpenAIService $openAIService, SpotifyService $spotifyService)
    {
        $this->openAIService = $openAIService;
        $this->spotifyService = $spotifyService;
    }

    public function index() {
        return view('welcome');
    }

    public function showEpisode($episodeId)
    {
        // Fetch or save episode data
        $episode = $this->spotifyService->saveEpisodeToDatabase($episodeId);

        return view('podcast.episode', compact('episode'));
    }



    public function showEpisodeList()
    {
        $episodes = PodcastEpisode::all();
        return view('podcast.episode_list', compact('episodes'));
    }

    //function to delete podcast episode 
    public function destroy($id)
    {
        $episode = PodcastEpisode::findOrFail($id); // Fetches the episode by ID or fails if not found
        $episode->delete(); // Deletes the episode from the database
    
        return redirect()->route('podcast.showEpisodeList')->with('success', 'Episode deleted successfully.');
    }




   // Function to search for podcast episodes MOVING TO LIVEWIRE
    public function searchEpisode(Request $request)
    {
        $query = $request->input('query');

        // Call the SpotifyService search method
        $episodes = $this->spotifyService->searchEpisode($query);

        if ($episodes) {
            return view('welcome', [
                'episodes' => $episodes, // Pass episodes directly
            ]);
        } else {
            return view('welcome', [
                'errorMessage' => 'No episode found for your search query.',
            ]);
        }
    } 


    public function searchEmbedding(Request $request)
    {
        $query = $request->input('query');
        $normQuery = strtolower(trim($query)); // Normalize query
        $cacheKey = "embedding_query_" . md5($normQuery); // Unique cache key
    
        // Step 1: Check cache for existing embedding
        $searchEmbedding = Cache::get($cacheKey);
    
        if (!$searchEmbedding) {
            // If not cached, generate embedding from OpenAI
            $searchEmbedding = $this->openAIService->searchEmbedding($query);
    
            // Cache the embedding for future use (e.g., 24 hours)
            Cache::put($cacheKey, $searchEmbedding, now()->addHours(24));
    
            // Log the cache miss
            Log::info("Cache miss for query: {$query}");
        } else {
            // Log the cache hit
            Log::info("Cache hit for query: {$query}");
        }
    
        // Step 2: Save search to the database
        $userId = auth()->id();
        if ($userId !== null) {
            UserSearch::updateOrCreate(
                ['user_id' => $userId, 'search_query' => $normQuery],
                ['search_embedding' => json_encode($searchEmbedding)]
            );
        }
    
        // Step 3: Fetch episode embeddings for similarity comparison
        $embeddings = DB::table('episode_embeddings')->get();
        $results = [];
    
        foreach ($embeddings as $embedding) {
            $vector = json_decode($embedding->vector, true);
            $similarity = $this->openAIService->cosineSimilarity($searchEmbedding, $vector);
    
            $results[] = [
                'embedding' => $embedding,
                'similarity' => $similarity,
                'spotify_id' => $embedding->spotify_id,
            ];
        }
    
        // Step 4: Sort and limit results
        usort($results, fn($a, $b) => $b['similarity'] <=> $a['similarity']);
        $topSpotifyIds = array_slice(array_map(fn($r) => $r['spotify_id'], $results), 0, 5);
    
        // Step 5: Fetch matching episodes
        $episodes = PodcastEpisode::whereIn('spotify_id', $topSpotifyIds)->get();
    
        // Step 6: Return episodes to the view
        return view('welcome', [
            'episodes' => $episodes,
            'query' => $query,
        ]);
    }
    
}
