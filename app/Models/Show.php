<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Show extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'genre'];

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }
}
