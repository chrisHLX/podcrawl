@extends('layouts.app')

@section('content')
    <h1>Podcast Episodes</h1>

    @if (!empty($episodes))
        @foreach ($episodes as $episode)
            <div>
                <h3>{{ $episode['title'] }}</h3>
                <p>{{ $episode['description'] }}</p>
                <a href="{{ route('podcast.showEpisode', ['episodeId' => $episode['id']]) }}">Listen</a>
            </div>
        @endforeach
    @else
        <p>No episodes found.</p>
    @endif
@endsection

<form action="{{ route('podcast.search') }}" method="GET">
    <input type="text" name="podcastName" placeholder="Search podcast by name" required>
    <button type="submit">Search</button>
</form>
