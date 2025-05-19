<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PodcastEpisode;
use App\Models\Transcript;
use App\Helpers\TextHelper;

class SearchDBController extends Controller
{
    public function searchDB(Request $request)
    {
        $query = $request->input('searchDB');
    
        if (!$query) {
            return redirect()->back()->with('error', 'Please enter a search term.');
        }
    
        $allPodcasts = PodcastEpisode::with(['hosts', 'guests'])
        ->where(function ($queryBuilder) use ($query) {
            $queryBuilder->where('name', 'LIKE', "%{$query}%")
                         ->orWhere('description', 'LIKE', "%{$query}%");
        })
        ->get();
    
    
        $transcripts = Transcript::where('content', 'LIKE', "%{$query}%")->get();

        // Pluck spotify_ids instead of podcast_episode_id
        $flaggedSpotifyIds = $transcripts->pluck('spotify_id')->unique();




        foreach ($allPodcasts as $podcast) {
            $podcast->hasMatchingTranscript = $flaggedSpotifyIds->contains($podcast->spotify_id);

            $podcast->highlighted_title = TextHelper::highlightMatch($podcast->title, $query);
            $podcast->highlighted_description = TextHelper::highlightMatch($podcast->description, $query);

            $podcast->transcript_snippet = null;

            logger("Transcript Spotify IDs: ", $flaggedSpotifyIds->toArray());
            logger("Podcast {$podcast->title} (spotify_id: {$podcast->spotify_id}) match: " . ($podcast->hasMatchingTranscript ? 'yes' : 'no'));
            if ($podcast->hasMatchingTranscript) {
                // Match the transcript by spotify_id, not podcast ID
                $transcript = $transcripts->firstWhere('spotify_id', $podcast->spotify_id);

                if ($transcript && $transcript->content) {
                    $sentences = preg_split('/(?<=[.?!])\s+/', $transcript->content);

                    foreach ($sentences as $sentence) {
                        if (stripos($sentence, $query) !== false) {
                            $podcast->transcript_snippet = TextHelper::highlightMatch($sentence, $query);
                            break;
                        }
                    }
                }
            }
        }
                

        return view('welcome', [
            'allpodcasts' => $allPodcasts,
            'dbquery' => $query
        ]);
    }
}
