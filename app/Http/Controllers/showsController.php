<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Show;
use App\Models\Rating;

class showsController extends Controller
{

    // Method to show user profile
    public function viewShows()
    {
        
        return view('Shows', ['Shows' => Show::all()]);
    }

    public function saveDescription(Request $request, $id)
    {
        $newTitle = Show::find($id);
        $newTitle->description = $request->description;
        $newTitle->save();

        return redirect('/show');
    }


}
