<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Episode_Embeddings extends Model
{
    use HasFactory;
    protected $table = 'episode_embeddings';  // Ensure this is correct
    // Add the fields you want to allow for mass assignment
    protected $fillable = [
        'name',
        'spotify_id',
        'vector'
    ];

}
