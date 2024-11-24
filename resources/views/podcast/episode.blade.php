<x-app-layout>
    <h1>{{ $episode->name }}</h1>
    <p>{{ $episode->description }}</p>
    <p><strong>Release Date:</strong> {{ $episode->release_date }}</p>
    <p><strong>Duration:</strong> {{ gmdate("H:i:s", $episode->duration_ms / 1000) }}</p>
    <p><strong>Show Name:</strong> {{ $episode->show_name }}</p>
    <p><strong>Listen on Spotify: </strong> <a href="{{ $episode->spotify_url }}" target="_blank">spotify.com</a></p>
    @if($episode->image_url)
        <img src="{{ $episode->image_url }}" alt="{{ $episode->name }}">
    @endif
</x-app-layout>
