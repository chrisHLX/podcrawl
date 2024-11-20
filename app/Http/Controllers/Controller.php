<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // Ensure the user is authenticated
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Method to show user profile
    public function profile()
    {
        $user = Auth::user(); // Retrieves the authenticated user
        return view('profile', compact('user'));
    }
}

abstract class Controller
{
    //
}
