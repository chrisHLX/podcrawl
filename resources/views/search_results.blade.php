@extends('layouts.app')

@section('content')
    <h1>Search Results</h1>

    @if (!empty($podcasts))
        @foreach ($podcasts as $podcast)
            <div>
                <h3>{{ $podcast['name'] }}</h3>
                <p>{{ $podcast['description'] }}</p>
                <a href="{{ route('podcast.showEpisodes', ['podcastId' => $podcast['id']]) }}">View Episodes</a>
            </div>
        @endforeach
    @else
        <p>No podcasts found.</p>
    @endif
@endsection
