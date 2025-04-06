<x-app-layout>
<!-- Trigger the modal if there's an error -->
@if (session('error'))
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            window.dispatchEvent(new CustomEvent('open-modal', { detail: 'sessionErrorModal' }));
        });
    </script>
@endif

<x-simple-content-wrapper>
<h1>Transcript Analysis Results</h1>

@if(isset($error))
    <div style="color: red;">
        <strong>Error:</strong> {{ $error }}
    </div>
@else
    @if(empty($topics))
        <p>No topics were detected in the transcript.</p>
    @else
        @foreach($topics as $topic)
                <h2>Part: {{ 1 + $topic['title'] ?? 'Unknown' }}</h2>
                <div class="topic overflow-auto">
                    <p>{!! nl2br($topic['chunk']) !!}</p>
                </div>
                <form action="{{ route('podcast.summarise')}}" method="POST">
                    @csrf
                    <input type="hidden" name="chunk" value="{{ $topic['chunk'] }}">
                    <input type="hidden" name="chunkID" value="{{ $topic['id'] }}">
                    <button type="submit">Summarise</button>
                    <button type="button">token count: {!! ceil( strlen(nl2br($topic['chunk'])) /4) !!}</button>
                 </form>
                 @if($topic->Tsummaries->isNotEmpty())
                    @foreach($topic->Tsummaries as $summary)
                        <h2>Summary by: {{ $summary->user->name }}</h2>
                        <p>{{ $summary->summary_text }}</p>
                    @endforeach
                @endif
        @endforeach
    @endif
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