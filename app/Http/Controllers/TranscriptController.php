<?php
namespace App\Http\Controllers;

use App\Models\Transcript;
use App\Models\TranscriptSection;
use Illuminate\Http\Request;
use App\Models\PodcastEpisode;
use App\Models\Tchunks;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class TranscriptController extends Controller
{
    // Add a transcript to the database
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

    

    public function createChunks(Request $request)
    {
        $transcript = $request->input('transcriptFull');
        $id = $request->input('transcript_id');
        
        // Split the transcript into chunks
        $chunks = $this->splitTrans($transcript, 10);
        
        // Fetch existing chunks for this transcript ID
        $existingChunks = Tchunks::where('transcript_id', $id)->get();
        
        foreach ($chunks as $index => $chunk) {
            if ($existingChunks->has($index)) {
                // Update the existing chunk
                $existingChunk = $existingChunks[$index];
                $existingChunk->update([
                    'title' => $index,
                    'chunk' => $chunk,
                ]);
            } else {
                // Create a new chunk
                Tchunks::create([
                    'title' => $index,
                    'chunk' => $chunk,
                    'transcript_id' => $id,
                ]);
            }
        }
    
        // Handle cases where there are more existing chunks than new ones
        if ($existingChunks->count() > count($chunks)) {
            $excessChunks = $existingChunks->slice(count($chunks));
            foreach ($excessChunks as $excessChunk) {
                $excessChunk->delete();
            }
        }
        
        // Fetch the updated or newly created chunks
        $updatedChunks = Tchunks::where('transcript_id', $id)->get();
        
        return view('podcast.transcripts', ['topics' => $updatedChunks]);
    }
    

    private function splitTrans($transcript, $parts = 6)
    {
        if (empty($transcript)) {
            Log::warning('Empty transcript provided.');
            return [];
        }

        // Define timestamp pattern
        //$timestampPattern = '/\b(?:\d{1,2}:)?\d{1,2}:\d{2}\b/';
        // Add newlines before and after timestamps
        //$transcript = preg_replace($timestampPattern, "\n$0\n", $transcript);

        // First checks if the transcript has a speaker change and makes sure to split it by " - " 
        $hasSpeakerChange = strpos($transcript, ' - ') !== false;
        // Handle very long transcripts
        if (Str::length($transcript) > 1_000_000) { // Example threshold
            Log::warning('Transcript too long to process in one go.');
            return ['error' => 'Transcript too long'];
        }
        
        if ($hasSpeakerChange) {
            // Split transcript by speaker changes
            $segments = Str::of($transcript)->split('/\s-\s/');
            $totalWords = array_reduce($segments->toArray(), function ($carry, $segment) {
                return $carry + str_word_count($segment);
            }, 0);
        
            // Calculate the maximum words per part
            $maxWordsPerPart = (int) ceil($totalWords / $parts);
            Log::info('Calculated Max Words Per Part: ', ['maxWordsPerPart' => $maxWordsPerPart]);
        
            $chunks = [];
            $currentChunk = '';
            $currentWordCount = 0;
        
            try {
                foreach ($segments as $segment) {
                    $segmentWordCount = str_word_count($segment);
        
                    // If adding this segment keeps the chunk within the max word count, add it
                    if ($currentWordCount + $segmentWordCount <= $maxWordsPerPart) {
                        $currentChunk .= ' - ' . $segment;
                        $currentWordCount += $segmentWordCount;
                    } else {
                        // If adding this segment exceeds the max word count, start a new chunk
                        if (!empty($currentChunk)) {
                            $chunks[] = trim($currentChunk);
                        }
                        $currentChunk = $segment;
                        $currentWordCount = $segmentWordCount;
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
        } else {
         
            // Split by word count and start new chunks at a "." Splits by . 
            $sentences = Str::of($transcript)->split('/(?<=\.)\s+/'); // Split by periods followed by spaces
            $totalWords = array_reduce($sentences->toArray(), function ($carry, $sentence) {
                return $carry + str_word_count($sentence);
            }, 0);

            // Calculate the maximum words per part
            $maxWordsPerPart = (int) ceil($totalWords / $parts);
            Log::info('Calculated Max Words Per Part: ', ['maxWordsPerPart' => $maxWordsPerPart]);

            $chunks = [];
            $currentChunk = '';
            $currentWordCount = 0;

            try {
                foreach ($sentences as $sentence) {
                    $sentenceWordCount = str_word_count($sentence);

                    // If adding this sentence keeps the chunk within the max word count, add it
                    if ($currentWordCount + $sentenceWordCount <= $maxWordsPerPart) {
                        $currentChunk .= ' ' . $sentence;
                        $currentWordCount += $sentenceWordCount;
                    } else {
                        // If adding this sentence exceeds the max word count, start a new chunk
                        if (!empty($currentChunk)) {
                            $chunks[] = trim($currentChunk);
                        }
                        $currentChunk = $sentence;
                        $currentWordCount = $sentenceWordCount;
                    }
                }

                // Add the last chunk
                if (!empty($currentChunk)) {
                    $chunks[] = trim($currentChunk);
                }

                Log::info('Split Successful FOR THE NO SPEAKER CHANGE: ', ['parts' => count($chunks)]);
                return $chunks;
            } catch (\Exception $e) {
                Log::error('Error splitting transcript: ', ['message' => $e->getMessage()]);
                return ['error' => 'Failed to split transcript'];
            }
        }
    }
    
    public function transcriptNLP(Request $request) {
        ini_set('max_execution_time', 60); // 60 seconds = 1 minutes

        // Get the raw transcript from the request
        $transcript = $request->input('transcriptFull');

        //split transcript
        $transcript = $this->splitTrans($transcript, 10);
        logger($transcript[0]);

        $transcript2 = $this->splitTrans($transcript[0], 4);


        // Path to the Python script
        $scriptPath = base_path('python-scripts/tsplits.py');


        foreach ($transcript as $index => $chunk) {
            $tempFilePath = storage_path("app/temp_transcript_{$index}.txt");
            file_put_contents($tempFilePath, $chunk);
        
            // Call Python script for this chunk
            $process = new Process(['python', $scriptPath, $tempFilePath]);
            $process->mustRun();
        
            // Collect the results
            $output = $process->getOutput();
            $results[] = json_decode($output, true);
        
            // Delete the temporary file
            unlink($tempFilePath);
        }
        
        // Merge all results into a single array
        $topics = array_merge(...$results);
        
        // return view('podcast.transcripts', ['topics' => $topics]);
        return view('podcast.transcripts', dd($topics));

    }


    private function splitTranscriptByTimestamps($transcript)
    {
        Log::info('Transcript passed', ['transcript' => $transcript]);
        // Regex to match timestamps with optional hours
        $timestampPattern = '/\b(?:\d{1,2}:)?\d{1,2}:\d{2}\b/';

        // Use preg_split to split the transcript by timestamps, keeping the delimiters (timestamps)
        $splitSections = preg_split($timestampPattern, $transcript, -1, PREG_SPLIT_DELIM_CAPTURE);

        // Initialize an array to store the labeled sections
        $sections = [];

        // Iterate through the split sections
        for ($i = 1; $i < count($splitSections); $i += 2) {
            // Use the timestamp as the key, and the following text as the value
            $timestamp = trim($splitSections[$i - 1]); // Timestamp
            $content = trim($splitSections[$i]);       // Content following the timestamp
            $sections[] = [
                'timestamp' => $timestamp,
                'content' => $content,
            ];
        }
        Log::info($sections);
        return $sections;
    }




}
