<?php

namespace App\Http\Controllers;

use App\Models\Person;
use App\Models\people;
use App\Http\Services\WikipediaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PeopleController extends Controller
{
    protected $wikipediaService;

    //create a new instance of WikipediaService
    public function __construct(WikipediaService $wikipediaService)
    {
        $this->wikipediaService = $wikipediaService;
    }

    public function showPerson(){
        $data = $this->wikipediaService->fetchPersonData("Jordan Peterson");
        $person = $data['description'];


        return $person;
    }

    public function showRequest($name)
    {
        // Attempt to find the person in the database by name
        $person = People::where('name', $name)->first();
    
        // If person has a description, use it and return the view right away
        if (!empty($person->description)) {
            $description = $person->description;
            logger("Description exists in the database. Using stored description.");
            
            return view('person', compact('person', 'description'));
        }
    
        // If no description is in the database, fetch data from Wikipedia
        $data = $this->wikipediaService->fetchPersonData($name);
    
        // Check if Wikipedia returned a description
        if ($data && isset($data['description'])) {
            $description = $data['description'];
            logger("Fetched description from Wikipedia.");
            
            $person->description = $description;
            $person->save();
    
        } else {
            // Wikipedia did not return a description
            $description = "No description available.";
            logger("Wikipedia did not return a description.");
        }
    
        // Finally, return the view with person and description data
        return view('person', compact('person', 'description'));
    }
    

}
