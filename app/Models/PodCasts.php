<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PodCasts extends Model
{
    use HasFactory;

    public function genre()
    {
        return $this->hasMany(genre::class);
    }
}
