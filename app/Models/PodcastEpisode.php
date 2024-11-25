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
        'show_name',
        'image_url',
        'spotify_url'
        // other fields you might need
    ];

    //defining the relationship between show and episode
    public function show()
    {
        return $this->belongsTo(Show::class, 'id');
    }
}
