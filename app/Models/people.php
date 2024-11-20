<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class people extends Model
{
    use HasFactory;
     // Specify the table name if it does not follow the default naming convention
     protected $table = 'people';

     // Specify the fillable attributes
     protected $fillable = [
         'name',
         'DOB',
         'rss',
         'interests',
         'description'
     ];
}
