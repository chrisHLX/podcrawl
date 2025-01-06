<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\PodcastEpisode;

class UserInfoController extends Controller
{
    public function UserInfo() {
        $userInfo = auth()->user();

        // Eager load the podcast episodes for the specific user    
        $user = User::with('podcastEpisodes')->find($userInfo->id);

        // Access the episodes
        $episodes = $user->podcastEpisodes;

        //dd($episodes);
        return view('dashboard', ['userInfo' => $userInfo->name, 'episodes' => $episodes]);
    }
}
