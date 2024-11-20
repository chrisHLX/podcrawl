<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WowClass extends Model
{
    use HasFactory;

    // Specify the table name if it does not follow the default naming convention
    protected $table = 'wow_classes';

    // Specify the fillable attributes
    protected $fillable = [
        'name',
        'spec',
    ];

    // Optionally, you can add any relationships or custom methods here
}
