<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\genre;

class genreController extends Controller
{   
    public function viewGenre() {
        return view('/genre');
    }

    public function new_genre(Request $request) {
        $genre = new genre;
        $genre->genre = $request->genre;
        
        $genre->save();

        return redirect('/genre');
    }
}
