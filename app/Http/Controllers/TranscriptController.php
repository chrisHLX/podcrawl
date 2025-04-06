<?php
namespace App\Http\Controllers;

use App\Models\Transcript;
use App\Models\TranscriptSection;
use Illuminate\Http\Request;
use App\Models\PodcastEpisode;
use App\Models\Tchunks;
use App\Models\TranscriptSummaries;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use App\Http\Services\OpenAIService;

class TranscriptController extends Controller
{
    protected $openAIService;

    public function __construct(OpenAIService $openAIService) {
        $this->openAIService = $openAIService;
    }
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
            Log::info('Validation passed', ["content" => Str::words($request->input('content'), 100, '')]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', ["errors" => $e->errors()]);
            return redirect()->back()->withErrors($e->errors());
        }
        
        # Format transcript if no punctuation or minimum punctuation.
        $requestText = $request->input('content');
        $formattedScript = $this->punk($requestText);
       
        $transcript = Transcript::create([
            'spotify_id' => $request->input('spotify_id'),
            'user_id' => auth()->id(),
            'episode_title' => $request->input('title'),
            'duration' => $request->input('duration'),
            'content' => $formattedScript
        ]);
        

        # $transcript->save();
        return redirect()->back()->with('success', 'Transcript created ');
    }
    
    // Update
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

    public function viewChunks(Request $request)
    {
        $id = $request->input('transcript_id');
        // $chunks = Tchunks::where('transcript_id', $id)->get();
        // Note getting an error tsummaries does not exist
        $chunks = Tchunks::with(['Tsummaries.user'])->where('transcript_id', $id)->get();

        return view('podcast.transcripts', ['topics' => $chunks]);
    }

    public function createChunks(Request $request)
    {
        $transcript = $request->input('transcriptFull');
        $id = $request->input('transcript_id');
        
        // Split the transcript into chunks
        $chunks = $this->splitTrans($transcript, 10);
        
        // Fetch existing chunks and map by 'title'
        $existingChunks = Tchunks::where('transcript_id', $id)->get()->keyBy('title');
        
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

    //Handling Chunk summaries
    public function summarise(Request $request) {
        $chunk = $request->input('chunk');
        $chunkID = $request->input('chunkID');
        $chunk = rawurlencode($chunk);;
        return view('podcast.summarise', ['chunk' => $chunk, 'chunkID' => $chunkID]);
    }

    // Summarise the chunk
    public function summariseChunk(Request $request)
    {
        $chunk = $request->input('chunk');
        $summaryType = $request->input('summaryType'); // e.g., "simple", "detailed", etc.

        // Perform summarization or text manipulation
        $summary = $this->summariseText($chunk, $summaryType);

        return response()->json(['summary' => $summary]);
    }

    

    public function saveSummary(Request $request)
    {
        logger('this is a test within the save request method');

        Log::info('the request data for saving the summary', [
                'data' => $request->input('tchunks_id'), 
                'other data' => $request->input('model'), 
                'text' => $request->input('summary_text')
            ]);

        $validated = $request->validate([
            'tchunks_id' => 'required|integer',
            'summary_text' => 'required|string',
            'model' => 'nullable|string'
        ]);
        $userID = auth()->id();
        Log::info('the request data for saving the summary after validation', [
            'data' => $validated['tchunks_id'], 
            'other data' => $validated['model'], 
            'text' => $validated['summary_text'],
            'user ID' => $userID
        ]);
        

        try {
            // updateOrCreate method uses two paramaters. The first specifies which attributes to match the second the attributes to update
            $summary = TranscriptSummaries::updateOrCreate(
                // First array: "Matching attributes" to find an existing record
                [
                    'user_id' => $userID,
                    'tchunks_id' => $validated['tchunks_id']
                ],
                // Second array: "Values to update or insert"
                [
                    'summary_text' => $validated['summary_text'],
                    'model' => $validated['model'] ?? 'manual'
                ]
            );

            return response()->json(['success' => true, 'summary_id' => $summary->id]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    // Private functions

    private function summariseText($text, $type)
    {
        // Example summarization logic
        Log::info('text being passed: ', ['text' => $text]);
        if ($type === 'simple') {
            return substr($text, 0, 100) . '...'; // Simple truncation
        } elseif ($type === 'detailed') {
            $prompt = "pretend you read the following transcript and you wrote a summary of the topics discussed" . $text;
            $text = $this->openAIService->CallOpenAI($prompt);

            // Decode the JSON response into an associative array
            $decodedResponse = json_decode($text, true);

            // Check if the response contains the generated text
            if (isset($decodedResponse['choices'][0]['message']['content'])) {
                Log::info('open ai response:', ['choices' => $decodedResponse['choices'][0]['message']['content']]);
                return $decodedResponse['choices'][0]['message']['content'];
            }
            return "Detailed summary of: $text"; // Placeholder for detailed summary logic
        }

        return $text; // Default return
    }
    

    private function splitTrans($transcript, $parts = 6)
    {
        // Handle empty transcript
        if (empty($transcript)) {
            Log::warning('Empty transcript provided.');
            return [];
        }

        // Clean and normalize the transcript
        $transcript = $this->normalizeTranscript($transcript);

        // Handle very long transcripts
        if (Str::length($transcript) > 1_000_000) { // Example threshold 
            Log::warning('Transcript too long to process in one go.');
            return ['error' => 'Transcript too long'];
        }

        // Split by speaker changes or sentence boundaries
        return $this->splitBySentence($transcript, $parts);
    }

    private function splitBySpeakerChange($transcript, $parts)
    {
        
        // Add newlines before and after timestamps
        $timestampPattern = '/\b(?:\d{1,2}:)?\d{1,2}:\d{2}\b/';
        $transcript = preg_replace($timestampPattern, "\n$0\n", $transcript);

        // Split transcript by speaker changes
        $segments = Str::of($transcript)->split('/\s-\s/');
        $totalWords = array_reduce($segments->toArray(), fn($carry, $segment) => $carry + str_word_count($segment), 0);

        $maxWordsPerPart = (int) ceil($totalWords / $parts);
        Log::info('Calculated Max Words Per Part (Speaker Change): ', ['maxWordsPerPart' => $maxWordsPerPart]);

        return $this->splitIntoChunks($segments, $maxWordsPerPart, ' - ');
    }

    private function splitBySentence($transcript, $parts)
    {
        // Split by sentences, preserving newlines before timestamps
        $sentences = Str::of($transcript)->split('/(?<=\.|\n)\s+/');

        // Calculate total words and words per part
        $totalWords = array_reduce($sentences->toArray(), fn($carry, $sentence) => $carry + str_word_count($sentence), 0);
        Log::info($totalWords);
        $maxWordsPerPart = (int) ceil($totalWords / $parts);

        Log::info('Calculated Max Words Per Part (No Speaker Change): ', ['maxWordsPerPart' => $maxWordsPerPart]);

        // Split sentences into chunks
        $chunks = $this->splitIntoChunks($sentences, $maxWordsPerPart);
        Log::info($chunks);
        // Apply preg_replace on each chunk
        $timestampPattern = '/\b(?:\d{1,2}:)?\d{1,2}:\d{2}\b/';
        $chunks = array_map(function ($chunk) use ($timestampPattern) {
            return preg_replace($timestampPattern, "\n$0\n", $chunk);
        }, $chunks);

        return $chunks;
    }



    private function splitIntoChunks($segments, $maxWordsPerPart, $delimiter = '')
    {
        $chunks = [];
        $currentChunk = '';
        $currentWordCount = 0;

        try {
            foreach ($segments as $segment) {
                $segmentWordCount = str_word_count($segment);

                if ($currentWordCount + $segmentWordCount <= $maxWordsPerPart) {
                    $currentChunk .= ($delimiter ? $delimiter : ' ') . $segment;
                    $currentWordCount += $segmentWordCount;
                } else {
                    if (!empty($currentChunk)) {
                        $chunks[] = trim($currentChunk);
                    }
                    $currentChunk = $segment;
                    $currentWordCount = $segmentWordCount;
                }
            }

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

    private function pythonPunct($transcript)
    {
        try {
            // Save transcript to a temporary file
            $temp_path = storage_path('app/tran_temp.txt');
            file_put_contents($temp_path, $transcript);

            // Set up the python script command
            $python_script = base_path('python-scripts/spas.py');
            $process = new Process(['python', $python_script]);
            $process->setTimeout(120);

            // Run the process
            $process->mustRun();

            // Wait for the processed file to be created
            $processed_path = storage_path('app/new_tran.txt');
            $max_attempts = 10;
            $attempt = 0;

            while (!file_exists($processed_path) && $attempt < $max_attempts) {
                usleep(50000);
                $attempt++;
            }

            if (!file_exists($processed_path)) {
                throw new \Exception("processed transcript file not found after waiting.");
            }

            // Read and return the processed transcript/file
            $processed_transcript = file_get_contents($processed_path);
            Log::info('Python Process Successful.', ['transcript' => $processed_transcript]);
            return $processed_transcript;

        } catch (ProcessFailedExeption $e) {
            Log::error("Python process failed: " . $e->getMessage());
            return "Error processing transcript.";
        } catch (\Exception $e) {
            Log::error("Error: " . $e->getMessage());
            return "Error processing transcript.";
        }
    }

    
    private function normalizeTranscript($transcript)
    {
        // Remove hidden characters like \n, \r, \t
        $transcript = preg_replace('/[\r\n\t]+/', ' ', $transcript);

        // Collapse multiple spaces into a single space
        $transcript = preg_replace('/\s+/', ' ', $transcript);

        // Trim leading and trailing spaces
        $transcript = trim($transcript);

        return $transcript;
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

    private function punk($transcript)
    {
        // Count words and full stops
        $wordcount = str_word_count($transcript);
        $fullstopcount = substr_count($transcript, '.');

        // Calculate the density of fullstops
        $density = $wordcount > 0 ? $fullstopcount / $wordcount : 0;

        // Set threshold: here we expect at least 1 full stop per 50 words (density of 0.02)
        $threshold = 1 / 50; // 0.02

        if ($density < $threshold) {
            // Insufficient punctuation – call the Python formatting script
            // Make sure to adjust the command (python3 and script path) as needed for your environment.
            logger("not enough punctuation sending it to python");
    
            // Optionally, you can capture the formatted transcript from $output if your Python script returns it.
            // For example, if your script prints the formatted transcript on the last line:

            return $transcript = $this->pythonPunct($transcript);

        } else {
            // The transcript has sufficient punctuation – process it as is.
            // Continue with the well-formatted transcript.
            logger("transcript has punctuation");

            return $transcript;
        }
    }




}
