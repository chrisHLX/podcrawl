<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\todoListController;
use App\Http\Controllers\podcastController;
use App\Http\Controllers\genreController;
use App\Http\Controllers\showsController;
use App\Http\Controllers\PeopleController;
use App\Http\Controllers\SummaryController;
use App\Http\Controllers\peopleDb;
use App\Http\Controllers\topicsController;
use App\Http\Controllers\UserInfoController;
use App\Http\Controllers\TranscriptController;
use App\Http\Controllers\searchDBController;


Route::get('/', [podcastController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');



Route::get('/dashboard', [UserInfoController::class, 'UserInfo'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//Topics
Route::get('podcast/addTopic/{id}', [topicsController::class, 'addNewTopic'])->middleware(['auth', 'verified'])->name('podcast.addTopic');

//genre route to save new genre
Route::get('genre', [genreController::class, 'viewGenre']);
Route::post('saveGenre', [genreController::class, 'new_genre'])->name('saveGenre');

//Route to display shows & save shows
Route::get('show', [showsController::class, 'viewShows']);
Route::post('show/{id}', [showsController::class, 'saveDescription'])->name('saveDescription');

/* ------ Search Routes ------- */

//Search Podcrawls DB
Route::get('/searchDB', [searchDBController::class, 'searchDB'])->name('podcast.searchDB');

//spotify API routes
Route::get('/search', [podcastController::class, 'searchPodcast'])->name('podcast.search');
Route::get('/person', [PeopleController::class, 'showPerson']);

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

/* ------ Transcript Routes ------- */

//Transcript 
Route::post('/transcript/create', [TranscriptController::class, 'manage'])->middleware(['auth', 'verified'])->name('transcript.manage');
Route::post('/podcast/addTranscript', [TranscriptController::class, 'create'])->middleware(['auth', 'verified'])->name('podcast.addTranscript');
Route::post('/podcast/transcriptChunks', [TranscriptController::class, 'viewChunks'])->middleware(['auth', 'verified'])->name('podcast.transcriptChunks');
Route::post('/podcast/createChunks', [TranscriptController::class, 'createChunks'])->middleware(['auth', 'verified'])->name('podcast.createChunks');

//Transcript summaries
Route::post('/podcast/summarise', [TranscriptController::class, 'summarise'])->middleware(['auth', 'verified'])->name('podcast.summarise');
Route::post('/summary/save', [TranscriptController::class, 'saveSummary'])->middleware(['auth', 'verified'])->name('summary.save');
Route::post('/podcast/summariseChunk', [TranscriptController::class, 'summariseChunk'])->middleware(['auth', 'verified'])->name('summarise.chunk');






//DYNAMIC ROUTES
Route::get('/person/{name}', [PeopleController::class, 'showRequest'])->name('person.showRequest');
require __DIR__.'/auth.php';
