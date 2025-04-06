<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PodcastEpisode; // Assuming you have a Podcast model
use App\Models\Transcript; // Assuming you have a Transcript model
use App\Models\People; // Assuming guests/hosts are stored in a 'persons' table

class SearchDBController extends Controller
{
    public function searchDB(Request $request)
    {
        $query = $request->input('searchDB');

        // Ensure a search query is provided
        if (!$query) {
            return redirect()->back()->with('error', 'Please enter a search term.');
        }

        /*
        // Search in podcast title and description
        $podcasts = PodcastEpisode::where('name', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->get();

        // Search in transcripts
        $transcripts = Transcript::where('content', 'LIKE', "%{$query}%")->get();

        // Search in guests and hosts (assuming they are stored in a 'persons' table and linked to podcasts)
        $people = People::where('name', 'LIKE', "%{$query}%")->with('podcasts')->get();

        // Merge podcast results with those found via transcripts or people
        $podcastsFromPeople = $people->pluck('podcasts')->flatten()->unique('id');
        $podcastsFromTranscripts = $transcripts->pluck('podcast')->unique('id');

        // Merge all results, ensuring unique podcast entries
        $allPodcasts = $podcasts->merge($podcastsFromPeople)->merge($podcastsFromTranscripts)->unique('id');

        */

        // Search podcast episode meta data /*
        
        // Search in podcast episodes
        $allPodcasts = PodcastEpisode::where('name', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->get();

        // Search in transcripts
        $transcripts = Transcript::where('content', 'LIKE', "%{$query}%")->get();

        // Create a list of flagged podcast IDs from matching transcripts
        $flaggedPodcastIds = $transcripts->pluck('podcast_episode_id')->unique();

        // Attach a flag to podcasts that have a matching transcript
        foreach ($allPodcasts as $podcast) {
            $podcast->hasMatchingTranscript = $flaggedPodcastIds->contains($podcast->id);
        }

        return view('welcome', [
            'allpodcasts' => $allPodcasts, 
            'dbquery' => $query
        ]);
    }
}
