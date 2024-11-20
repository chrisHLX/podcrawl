<?php

namespace App\Http\Controllers;

use App\Http\Services\OpenAIService;
use Illuminate\Http\Request;

class SummaryController extends Controller
{
    protected $openAIService;

    public function __construct(OpenAIService $openAIService)
    {
        $this->openAIService = $openAIService;
    }

    public function showForm()
    {
        // Just show the form initially without any summary
        return view('summarize');
    }

    public function summarize(Request $request)
    {
        // Validate the input text
        $request->validate([
            'text' => 'required|string',
        ]);

        // Get the input text from the form
        $text = $request->input('text');

        // Call the OpenAI service to summarize the text
        $result = $this->openAIService->summarizeText($text);

        // Extract the summary from the response
        $summary = $result['choices'][0]['message']['content'] ?? 'No summary available';

        // Pass the summary back to the view
        return view('summarize', compact('summary'));
    }

    public function showMatch()
    {
        return view("match_history");
    }

    public function submit(Request $request){
         // Validate the input
         $request->validate([
            'class' => 'required|string',
            'match_data' => 'required|string',
        ]);
    }

}
