<?php

namespace App\Http\Controllers;

use App\Models\people;
use App\Http\Services\WikipediaService;
use Illuminate\Http\Request;

class peopleDB extends Controller
{
    

    public function getPeople(){
        $peoples = people::all();
        return view('/podcast_people', compact('peoples'));
    }

    


}
