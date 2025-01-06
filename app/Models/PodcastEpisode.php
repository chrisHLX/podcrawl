<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PodcastEpisode extends Model
{
    use HasFactory;

    // Add the fields you want to allow for mass assignment
    protected $fillable = [
        'spotify_id',
        'name',
        'title',
        'release_date',
        'duration_ms',
        'description',
        'language',
        'show_name', // if show name in this table matches the name of shows in the shows table then link the two but really this should just be a foreign key 
        'image_url',
        'spotify_url',
        'user_id'
        // other fields you might need
    ];

    //defining the relationship between show and episode
    public function show()
    {
        return $this->belongsTo(Show::class, 'id');
    }

    public function people()
    {
        return $this->belongsToMany(People::class, 'people_podcast_episode')
                    ->withPivot('role') // Include the role field in queries
                    ->withTimestamps(); // Automatically manage pivot timestamps
    }

    public function hosts()
    {
        return $this->people()->wherePivot('role', 'host');
    }

    public function guests()
    {
        return $this->people()->wherePivot('role', 'guest');
    }

    // For getting the user that added the episode
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transcripts()
    {
        return $this->hasOne(Transcript::class, 'spotify_id', 'spotify_id');
    }

    public function topics()
    {
        return $this->hasMany(EpisodeUserTopics::class, 'spotify_id', 'spotify_id');
    }



}
