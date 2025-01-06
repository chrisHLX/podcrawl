<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\EpisodeUserTopics;
use App\Models\PodcastEpisode;
use App\Http\Services\OpenAIService;


class topicsController extends Controller
{
    protected $openAIService;

    public function __construct(OpenAIService $openAIService)
    {
        $this->openAIService = $openAIService;
    }
    
    public function addNewTopic(Request $request, $id)
    {
        $request = $request->input('topic');

        $nWord = $this->openAIService->wordCheck($request);
        logger('THE N WORD IS' . $nWord);
        
        $user = auth()->id();
        $spotify = $id;
        logger($request . ' the user: ' . $user . ' spotify_id ' . $id);
        
        if (EpisodeUserTopics::where('spotify_id', $spotify)
            ->where('topic', $request)
            ->exists()) {
            return redirect()->back()->with('error', 'Topic already exists.');
        }


        EpisodeUserTopics::create([
            'spotify_id' => $spotify,
            'user_id' => $user, 
            'topic' => $request
        ]);

        return redirect()->back()->with('success', 'topic created');
    }

}