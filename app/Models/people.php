<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class people extends Model
{
    use HasFactory;
     // Specify the table name if it does not follow the default naming convention
     protected $table = 'people';

     protected $casts = [
        'aliases' => 'array',
    ];
    
     // Specify the fillable attributes
     protected $fillable = [
         'name',
         'DOB',
         'rss',
         'interests',
         'description',
         'aliases'
     ];

     public function podcastEpisodes()
    {
        return $this->belongsToMany(PodcastEpisode::class, 'people_podcast_episode')
                    ->withPivot('role') // Include the role field in queries
                    ->withTimestamps(); // Automatically manage pivot timestamps
    }

    public function hostedEpisodes()
    {
        return $this->podcastEpisodes()->wherePivot('role', 'host');
    }

    public function guestEpisodes()
    {
        return $this->podcastEpisodes()->wherePivot('role', 'guest');
    }

}
