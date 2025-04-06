<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tchunks extends Model
{
    //
    protected $fillable = ['title', 'chunk', 'transcript_id'];

    public function Transcript() 
    {
        return $this->belongsTo(Transcript::class);
    }

    public function Tsummaries() 
    {
        return $this->hasMany(TranscriptSummaries::Class);
    }
}

