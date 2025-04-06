<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\PodcastEpisode;
use App\Models\TranscriptSummaries;
use Illuminate\Support\Facades\Log;

class UserInfoController extends Controller
{
    public function UserInfo() {
        $userInfo = auth()->user();

        // Eager load the summaries and nested relationships to get the episode title
        $user = User::with([
            'summaries.Tchunks.Transcript', // Load summaries and the nested relationships
            'podcastEpisodes' // Optionally, keep podcastEpisodes if needed
        ])->find($userInfo->id);
        
        // Access the summaries
        $summaries = $user->summaries;
        
        // Access the episodes
        $episodes = $user->podcastEpisodes;
        
        //dd($episodes);
        return view('dashboard', ['userInfo' => $userInfo->name, 'episodes' => $episodes, 'summaries' => $summaries]);
    }

    // copy of my original user info function because ai made a better version that I dont understand yet
    public function UserInfoMyVersionForReference() {
        $userInfo = auth()->user();

        // Eager load the podcast episodes for the specific user    
        $user = User::with('podcastEpisodes')->find($userInfo->id);
        $summaries = User::with('summaries')->find($userInfo->id);
        
        // Access the episodes
        $episodes = $user->podcastEpisodes;
        $summaries = $user->summaries;
        //dd($episodes);
        return view('dashboard', ['userInfo' => $userInfo->name, 'episodes' => $episodes, 'summaries' => $summaries]);
    }
}
