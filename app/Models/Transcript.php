<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transcript extends Model
{
    use HasFactory;

    protected $fillable = ['spotify_id', 'user_id', 'episode_title', 'content'];

    public function sections()
    {
        return $this->hasMany(TranscriptSection::class);
    }

    public function episode()
    {
        return $this->belongsTo(PodcastEpisode::class, 'spotify_id', 'spotify_id');
    }

    public function Tchunks()
    {
        return $this->hasMany(Tchunks::class);
    }
}
