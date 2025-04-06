<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TranscriptSummaries extends Model
{
    protected $fillable = ['user_id', 'tchunks_id', 'summary_text', 'model'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function Tchunks()
    {
        return $this->belongsTo(Tchunks::class);
    }
}

