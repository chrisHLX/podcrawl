


    <h1>Search Podcasts</h1>

    @if (!empty($podcasts))
        <h2>Search Results</h2>
        <ul>
            @foreach ($podcasts as $podcast)
                <li>
                    <strong>{{ $podcast['name'] }}</strong><br>
                    <em>{{ $podcast['description'] }}</em><br>
                    <a href="{{ route('podcast.showEpisodes', ['podcastId' => $podcast['id']]) }}">View Episodes</a>
                </li>
            @endforeach
        </ul>
    @elseif (isset($error))
        <p>{{ $error }}</p>
    @endif

    <hr>

    <form action="{{ route('podcast.search') }}" method="GET">
        <input type="text" name="podcastName" placeholder="Search podcast by name" value="{{ request('podcastName') }}" required>
        <button type="submit">Search</button>
    </form>

