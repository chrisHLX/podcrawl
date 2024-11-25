<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Show;
use App\Models\Rating;
use App\Http\Services\SpotifyService;
use Illuminate\Support\Facades\Log;

class showsController extends Controller
{
    protected $spotifyService;

    public function __construct(SpotifyService $spotifyService)
    {
        $this->spotifyService = $spotifyService;
    }


    public function saveDescription(Request $request, $id)
    {
        $newTitle = Show::find($id);
        $newTitle->description = $request->description;
        $newTitle->save();

        return redirect('/show');
    }

        // Function to search for podcast Show based on the show name in episodes
        public function searchShow($showName)
        {
            // we base 64 encoded the show name in the url to protect against special characters
            $showName = base64_decode($showName);
            // Call the SpotifyService search method
            $shows = $this->spotifyService->searchShow($showName);
    
            if ($shows) {
                return view('podcast.shows', [
                    'shows' => $shows, // Pass show directly
                ]);
            } else {
                return view('podcast.shows', [
                    'errorMessage' => 'No show found for your search query.',
                ]);
            }
        }

    public function getShow($showId)
    {
        $showData = $this->spotifyService->getShowData($showId);
        return view('podcast.show', [
            'show' => $showData,
        ]);
    }

    public function getShowList(){
        return view('podcast.Show_list', ['shows' => Show::all()]);
    }

}
