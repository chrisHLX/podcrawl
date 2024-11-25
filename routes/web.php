<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\todoListController;
use App\Http\Controllers\podcastController;
use App\Http\Controllers\genreController;
use App\Http\Controllers\showsController;
use App\Http\Controllers\PeopleController;
use App\Http\Controllers\SummaryController;
use App\Http\Controllers\peopleDb;



Route::get('/', [todoListController::class, 'index']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('saveItemRoute', [todoListController::class, 'saveItem'])->name('saveItem');

Route::post('markComplete/{id}', [todoListController::class, 'markComplete'])->name('markComplete');

//save podcast episode route
Route::post('savePodcast', [podcastController::class, 'test'])->name('savePodcast');

//genre route to save new genre
Route::get('genre', [genreController::class, 'viewGenre']);
Route::post('saveGenre', [genreController::class, 'new_genre'])->name('saveGenre');

//Route to display shows & save shows
Route::get('show', [showsController::class, 'viewShows']);
Route::post('show/{id}', [showsController::class, 'saveDescription'])->name('saveDescription');


//Taddy API routes
Route::get('/search', [podcastController::class, 'searchPodcast'])->name('podcast.search');

Route::get('/person', [PeopleController::class, 'showPerson']);


//AI
// Route to display the summarize form
Route::get('/summarize', [SummaryController::class, 'showForm']);

// Route to handle form submission and return the summary
Route::post('/summarize', [SummaryController::class, 'summarize']);

//Match History
Route::get('/match-history', [SummaryController::class, 'showMatch'])->name('matches.form');
Route::post('/match-history/submit', [SummaryController::class, 'submit'])->name('matches.submit');


//People
Route::get('/podcast_people', [peopleDb::class, 'getPeople'])->middleware(['auth', 'verified'])->name('podcast_people');



//podcast episodes list
Route::get('/podcast/episode_list', [podcastController::class, 'showEpisodeList'])->middleware(['auth', 'verified'])->name('podcast.showEpisodeList');
Route::delete('/podcast/episodes/{id}', [PodcastController::class, 'destroy'])->middleware(['auth', 'verified'])->name('podcast.episodes.destroy');

//search function spotify
Route::get('/podcast/search', [PodcastController::class, 'searchEpisode'])->name('podcast.search');

//Get podcast Episode information based on the ID (we use the search function to get the id which we can then pass to this route) if its not in the database we save it
Route::get('/podcast/episode/{episodeId}', [podcastController::class, 'showEpisode'])->middleware(['auth', 'verified'])->name('podcast.showEpisode');

//get the podcast show
Route::get('/podcast/shows/{showName}', [showsController::class, 'searchShow'])->middleware(['auth', 'verified'])->name('podcast.shows');
Route::get('/podcast/showsID/{showId}', [showsController::class, 'getShow'])->middleware(['auth', 'verified'])->name('podcast.show');

//get the podcast shows from the database
Route::get('/shows', [showsController::class, 'getShowList'])->middleware(['auth', 'verified'])->name('podcast.show_list');


//DYNAMIC ROUTES
Route::get('/person/{name}', [PeopleController::class, 'showRequest'])->name('person.showRequest');
require __DIR__.'/auth.php';
