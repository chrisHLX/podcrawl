<x-app-layout>
    <x-simple-content-wrapper>
    <h1>{{ $episode->name }}</h1>
    <p><h2><strong>Topics:</strong> add your own unique topic so everyone will be able to reference it! </h2></p>
    @if($episode->topics->isNotEmpty())
    <ul>
        @foreach($episode->topics as $topic)
            <li>{{ $topic->topic }} added by {{ $topic->user->name }}</li>
        @endforeach
    </ul>
    @endif
     <form action="{{ route('podcast.addTopic', $episode->spotify_id)}}" method="GET">
     @csrf
                <input type="text" name="topic" placeholder="Add a topic" maxlength="25" required>
                <button type="submit">Add Topic</button>
    </form>
    @if($episode->image_url)
        <img src="{{ $episode->image_url }}" alt="{{ $episode->name }}">
    @endif
    <div>
        <h1>Description</h1>
    
        <div x-data="{ expanded: false }">
            <button type="button" x-on:click="expanded = ! expanded">
                <span x-show="! expanded">Description</span>
                <span x-show="expanded">Hide</span>
            </button>
            <div x-show="expanded">
                {{ $episode->description }}
            </div>              
        </div>
    </div>
    <h2>Add Transcript</h2>
    @if($episode->transcripts && $episode->transcripts->content)
        @if($episode->transcripts->Tchunks)    
            <form action="{{ route('podcast.transcriptChunks')}}" method="POST">
            @csrf
                        <input type="hidden" name="transcript_id" value="{{ $episode->transcripts->id }}">
                        <button type="submit">View Chunks</button>
            </form>
        @else
            <form action="{{ route('podcast.createChunks')}}" method="POST">
            @csrf
                        <input type="hidden" name="transcriptFull" value="{{ $episode->transcripts->content }}">
                        <input type="hidden" name="transcript_id" value="{{ $episode->transcripts->id }}">
                        <button type="submit">Create Chunks</button>
            </form>
        @endif 
    <div class="overflow-auto">      
        {{ $episode->transcripts->content }}
    </div>
    @else
    <div x-data>
    <!-- Trigger Transcript Button -->

    <button
        x-on:click="$dispatch('open-modal', 'addTranscriptModal')"
        class="px-4 py-2 bg-blue-500 text-white rounded">
        Add Transcript
    </button>
    @endif

        <!-- Modal for Transcripts -->
         <!-- Episode name, Spotify ID, User ID, Content -->
        <form action="{{ route('podcast.addTranscript') }}" method="POST">
            @csrf
            <x-modal name="addTranscriptModal" :show="false" maxWidth="lg">
                <div class="px-6 py-4">
                    <h2 class="text-lg font-medium text-gray-900">Add Transcript</h2>

                    <div class="mt-4">
                        <label for="episode" class="block text-sm font-medium text-gray-700">{{ $episode->name }}</label>
                        <input type="hidden" name="title" value="{{ $episode->name }}">
                        <input type="hidden" name="spotify_id" value="{{ $episode->spotify_id }}">
                        <input type="hidden" name="duration" value="{{ $episode->duration_ms }}">
                    </div>

                    <div class="mt-4">
                        <label for="transcript" class="block text-sm font-medium text-gray-700">Transcript</label>
                        <textarea
                            id="transcript"
                            rows="5"
                            name="content"
                            class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring focus:ring-opacity-50"
                            placeholder="Write the transcript here"></textarea>
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-100 text-right">
                    <button
                        type="button"
                        class="px-4 py-2 bg-gray-600 text-white rounded"
                        x-on:click="$dispatch('close-modal', 'addTranscriptModal')">
                        Cancel
                    </button>

                    <button
                        type="submit"
                        class="ml-2 px-4 py-2 bg-blue-500 text-white rounded"
                        x-on:click="$dispatch('close-modal', 'addTranscriptModal')">
                        Save
                    </button>
                </div>
            </x-modal>
        </form>
    </div>



    <p><strong>Release Date:</strong> {{ $episode->release_date }}</p>
    <p><strong>Duration:</strong> {{ gmdate("H:i:s", $episode->duration_ms / 1000) }}</p>
    <p><strong>Show Name:</strong> {{ $episode->show_name }}</p>
    <p><strong>Find Show:</strong> <a href="/podcast/shows/{{ base64_encode($episode->show_name) }}">{{ $episode->show_name }}</a>
    <p><strong>Listen on Spotify: </strong> <a href="{{ $episode->spotify_url }}" target="_blank">spotify.com</a></p>


    <!-- Trigger the modal if there's an error -->
    @if (session('error'))
        <script>
            window.addEventListener('DOMContentLoaded', () => {
                window.dispatchEvent(new CustomEvent('open-modal', { detail: 'sessionErrorModal' }));
            });
        </script>
    @endif
    

    <!-- Modal for Session Errors -->
    <x-modal name="sessionErrorModal" maxWidth="sm">
        <div class="p-6">
            <h2 class="text-lg font-medium text-red-600">Error</h2>
            <p class="mt-4 text-sm text-gray-600">
                {{ session('error') }}
            </p>
            <div class="mt-6 flex justify-end">
                <button x-on:click="$dispatch('close-modal', 'sessionErrorModal')" class="px-4 py-2 bg-gray-800 text-white rounded">
                    Close
                </button>
            </div>
        </div>
    </x-modal>

    

</x-simple-content-wrapper>
</x-app-layout>
