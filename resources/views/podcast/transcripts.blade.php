<x-app-layout>
<!-- Trigger the modal if there's an error -->
@if (session('error'))
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            window.dispatchEvent(new CustomEvent('open-modal', { detail: 'sessionErrorModal' }));
        });
    </script>
@endif

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
            <div class="topic">
                <h3>Topic: {{ $topic['title'] ?? 'Unknown' }}</h3>
                <p>{{ $topic['content'] }}</p>
                @if(isset($topic['timestamp']))
                    <p class="timestamp">Timestamp: {{ $topic['timestamp'] }}</p>
                @endif
            </div>
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



</x-app-layout>