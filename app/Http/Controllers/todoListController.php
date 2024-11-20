<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\listItem;
use App\Models\PodCasts;

class todoListController extends Controller
{

    public function index() {
        return view('welcome', ['listItems' => listItem::where('id', '>', 5)->get()], ['podCasts' => PodCasts::all()]);
    }

    public function saveItem(Request $request){

        $newlistItem = new listItem;
        $newlistItem->name = $request->listItem;
        $newlistItem->is_complete = 0;
        $newlistItem->save();

        return redirect('/');

    }

    public function markComplete($id) {

        $lItem = listItem::find($id);
        $lItem->is_complete = "1";
        $lItem->save();

        return redirect('/');
    }

    //
}
