<?php

namespace App\Observers;

use App\Models\Show;
use App\Models\People;
use App\Http\Services\OpenAIService;
use Illuminate\Support\Facades\Log;

class ShowObserver
{
    protected $openAIService;
    

    public function __construct(OpenAIService $openAIService)
    {
        $this->openAIService = $openAIService;
    }
    /**
     * Handle the Show "created" event.
     */
    public function created(Show $show)
    {
        //so when a show is created we are searching our people table for a person with the same name as the publisher. Only works if publisher is a person
       
       $person = People::where('name', $show->publisher)->first();
        
       if ($person) {

            //update the foreign id field in host to the person id
            $show->host_id = $person->id;
            $show->save();
       } 
       else {
        Log::info('Person doesnt exist so no we will make one');
            //so once again lets make them exist

            $result = $this->openAIService->getPeopleData($show->name, "podcast episode information unavailable", $show->publisher, 'host');
            Log::info('chat gpt response after it has been passed back to the observer {res}', ['res' => $result]);

            Log::info('Chat GPT Response', [
                'Host' => [
                    'Name' => $result['host']['name'] ?? 'Unknown',
                    'DOB' => $result['host']['DOB'] ?? 'Not provided',
                    'Podcast Name' => $result['host']['rss'] ?? 'Not provided',
                    'Interests' => $result['host']['interests'] ?? 'Not provided',
                    'Description' => $result['host']['description'] ?? 'Not provided',
                    'Aliases' => $result['host']['aliases'] ?? 'Not provided',
                ],
            ]);


            $person = People::create([
                'name' => $result['host']['name'],
                'DOB' => $result['host']['DOB'],
                'rss' => $result['host']['rss'],
                'interests' => $result['host']['interests'],
                'description' => $result['host']['description'],
                'aliases' => $result['host']['aliases']
            ]);
            
            $show->host_id = $person->id;
            $show->save();
       }

    }

    /**
     * Handle the Show "updated" event.
     */
    public function updated(Show $show): void
    {
        //
    }

    /**
     * Handle the Show "deleted" event.
     */
    public function deleted(Show $show): void
    {
        //
    }

    /**
     * Handle the Show "restored" event.
     */
    public function restored(Show $show): void
    {
        //
    }

    /**
     * Handle the Show "force deleted" event.
     */
    public function forceDeleted(Show $show): void
    {
        //
    }
}
