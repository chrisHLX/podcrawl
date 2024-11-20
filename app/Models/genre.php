<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class genre extends Model
{
    use HasFactory;
    protected $table = 'genre';
    // Disable automatic timestamps
    public $timestamps = false;

    public function PodCasts()
    {
        return $this->belongsTo(PodCasts::class);
    }
}
