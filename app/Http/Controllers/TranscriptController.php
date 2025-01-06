<?php
namespace App\Http\Controllers;

use App\Models\Transcript;
use App\Models\TranscriptSection;
use Illuminate\Http\Request;
use App\Models\PodcastEpisode;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class TranscriptController extends Controller
{
    public function create(Request $request)
    {
        Log::info('Function called', ["title" => $request->input('title')]);
    
        try {
            $request->validate([
                'spotify_id' => 'required|string',
                'title' => 'nullable|string',
                'content' => 'required|string',
                'duration' => 'required|integer',
            ]);
            Log::info('Validation passed', ["content" => $request->input('content')]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', ["errors" => $e->errors()]);
            return redirect()->back()->withErrors($e->errors());
        }
    

        $transcript = Transcript::create([
            'spotify_id' => $request->input('spotify_id'),
            'user_id' => auth()->id(),
            'episode_title' => $request->input('title'),
            'duration' => $request->input('duration'),
            'content' => $request->input('content')
        ]);
        
        $transcript->save();
        return redirect()->back()->with('success', 'Transcript created ');
    }
    

    public function updateSection(Request $request, $id)
    {
        $request->validate(['content' => 'required|string']);
        $section = TranscriptSection::findOrFail($id);
        $section->update(['content' => $request->content]);

        return response()->json(['message' => 'Section updated successfully.']);
    }

    public function show($id)
    {
        $transcript = Transcript::with('sections')->findOrFail($id);
        return view('podcast.transcripts', compact('transcript'));
    }

    public function manage($episodeId)
    {
        $episode = PodcastEpisode::with('transcripts.sections')->findOrFail($episodeId);

        return view('podcast.transcripts', [
            'episode' => $episode,
            'transcripts' => $episode->transcripts, // Fetch all transcripts linked to this episode
        ]);
    }

    public function transcriptNLP(Request $request) {
        
        // Get the raw transcript from the request
        $transcript = $request->input('transcriptFull');

        //split transcript
        $transcript = $this->splitTrans($transcript, 10);
        logger($transcript[0]);

        $tempFilePath = storage_path('app/temp_transcript.txt');
        file_put_contents($tempFilePath, $transcript[0]);

        // Path to the Python script
        $scriptPath = base_path('python-scripts/nlp.py');
        
        // Create the command to execute the Python script
        $process = new Process(['python', $scriptPath, $tempFilePath]);

        try {
            // Run the process
            $process->mustRun();

            // Get the output from the Python script
            $output = $process->getOutput();
            
            // Decode the JSON output
            $topics = json_decode($output, true);

            return view('podcast.transcripts', ['topics' => $topics]);
            } catch (ProcessFailedException $e) {
                logger($e->getMessage());
                return view('podcast.transcripts', [
                    'error' => $e->getMessage(),
                ]);
            }
        
    }

    private function splitTrans($transcript, $parts = 6)
    {
        if (empty($transcript)) {
            Log::warning('Empty transcript provided.');
            return [];
        }
    
        $totalLength = Str::length($transcript);
        Log::info('Transcript Length: ', ['length' => $totalLength]);
    
        // Handle very long transcripts
        if ($totalLength > 1_000_000) { // Example threshold
            Log::warning('Transcript too long to process in one go.');
            return ['error' => 'Transcript too long'];
        }
    
        // Calculate the part length in characters
        $partLength = (int) ceil($totalLength / $parts);
        Log::info('Calculated Part Length: ', ['partLength' => $partLength]);
    
        try {
            // Split the transcript into words and then chunk them based on length, not the number of words
            $words = Str::of($transcript)->split('/\s+/');
            $chunks = [];
            $currentChunk = '';
    
            foreach ($words as $word) {
                // Add the word to the current chunk and check the length
                if (Str::length($currentChunk . ' ' . $word) <= $partLength) {
                    $currentChunk .= ' ' . $word;
                } else {
                    // If adding this word exceeds the part length, start a new chunk
                    $chunks[] = trim($currentChunk);
                    $currentChunk = $word;
                }
            }
    
            // Add the last chunk
            if (!empty($currentChunk)) {
                $chunks[] = trim($currentChunk);
            }
    
            Log::info('Split Successful: ', ['parts' => count($chunks)]);
            return $chunks;
        } catch (\Exception $e) {
            Log::error('Error splitting transcript: ', ['message' => $e->getMessage()]);
            return ['error' => 'Failed to split transcript'];
        }
    }
    



}
