<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Show extends Model
{
    use HasFactory;

    // Add the fields you want to allow for mass assignment
    protected $fillable = [
        'name',
        'publisher',
        'description',
        'genre',
        'spotify_id',
        'image_url',
        'spotify_url'
        // other fields you might need
    ];

    //defing the relationship between show and host... host_id is a foreign key in the shows table but points to an id in the people table
    public function host()
    {
        return $this->belongsTo(People::class, 'host_id');
    }

    public function episodes()
    {
        return $this->hasMany(PodcastEpisode::class);
    }
}
