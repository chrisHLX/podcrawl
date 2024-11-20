<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\PodCasts;
use App\Models\PodcastEpisode;
use App\Http\Services\TaddyPodcastService;
use App\Http\Services\SpotifyService;

class podcastController extends Controller
{
    protected $taddyPodcastService;
    protected $spotifyService;

    public function __construct(TaddyPodcastService $taddyPodcastService, SpotifyService $spotifyService)
    {
        $this->taddyPodcastService = $taddyPodcastService;
        $this->spotifyService = $spotifyService;
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




    // Function to search for podcast episodes
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

        

}
