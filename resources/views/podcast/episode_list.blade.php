<x-app-layout>
    @foreach ($episodes as $episode)
        <div>
            <h3>{{ $episode->name }}</h3>
            <p><strong>Release Date:</strong> {{ $episode->release_date }}</p>
            <p><strong>Duration:</strong> {{ gmdate("H:i:s", $episode->duration_ms / 1000) }}</p>
            <p><strong>Description:</strong> {{ $episode->description }}</p>
            <p><strong>Language:</strong> {{ $episode->language }}</p>
            <p><strong>Show Name:</strong> {{ $episode->show_name }}</p>
            <p><strong>Listen on Spotify</strong>{{ $episode->spotify_url }}</p>

            <img src="{{ $episode->image_url }}" alt="Episode Image" style="width:100px; height:auto;">
            <form action="{{ route('podcast.episodes.destroy', $episode->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this episode?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 ...">Delete</button>
            </form>
        </div>
        <hr>
    @endforeach
</x-app-layout>
