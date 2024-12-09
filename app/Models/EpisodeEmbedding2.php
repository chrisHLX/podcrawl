<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EpisodeEmbedding2 extends Model
{
    use HasFactory;
    protected $table = 'episode_embeddings2';  // Ensure this is correct
    // Add the fields you want to allow for mass assignment
    protected $fillable = [
        'name',
        'spotify_id',
        'vector'
    ];

}
