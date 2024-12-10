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



Route::get('/', [podcastController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


//genre route to save new genre
Route::get('genre', [genreController::class, 'viewGenre']);
Route::post('saveGenre', [genreController::class, 'new_genre'])->name('saveGenre');

//Route to display shows & save shows
Route::get('show', [showsController::class, 'viewShows']);
Route::post('show/{id}', [showsController::class, 'saveDescription'])->name('saveDescription');


//qspotify API routes
Route::get('/search', [podcastController::class, 'searchPodcast'])->name('podcast.search');
Route::get('/person', [PeopleController::class, 'showPerson']);


//AI


//People
Route::get('/podcast_people', [peopleDb::class, 'getPeople'])->middleware(['auth', 'verified'])->name('podcast_people');
Route::get('/podcast/people/{id}', [peopleDb::class, 'getPeople'])->middleware(['auth', 'verified'])->name('podcast_people');


//podcast episodes list
Route::get('/podcast/episode_list', [podcastController::class, 'showEpisodeList'])->middleware(['auth', 'verified'])->name('podcast.showEpisodeList');
Route::delete('/podcast/episodes/{id}', [PodcastController::class, 'destroy'])->middleware(['auth', 'verified'])->name('podcast.episodes.destroy');

//search function spotify


Route::get('/podcast/search', [PodcastController::class, 'searchEpisode'])->name('podcast.search');
Route::get('/podcast/search/embeddings', [PodcastController::class, 'searchEmbedding'])->name('podcast.search.embeddings'); //vector search


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
