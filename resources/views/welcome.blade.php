<x-app-layout>
    
    <div class="bg-gray-50 text-black/50 dark:bg-black dark:text-white/50">
        <div class="center">
            <h1>Podcrawls</h1>
            <p>Welcome to podcrawls, a website for all things podcast!</p>

            <!-- Search Form using spotify -->
            <form action="{{ route('podcast.search') }}" method="GET">
                <input type="text" name="query" placeholder="Search for an episode..." required>
                <button type="submit">Search Spotify</button>
            </form>

            <!-- Search our podcasts using vectors -->
            <form action="{{ route('podcast.search.embeddings') }}" method="GET">
                <input type="text" name="query" placeholder="Search using vectors..." required>
                <button type="submit">Search Vector</button>
            </form>


            <p>View our podcast people <a href="/podcast_people">PEOPLE</a></p>
            <p>View our podcast episodes <a href="/podcast/episode_list">EPISODES</a></p>
            <p>View our podcast shows <a href="/shows">SHOWS</a>
        </div>
    </div>
    <x-simple-content-wrapper>
    <!-- Display search results if available -->
    <h1>Search Results</h1>
    
    @if(isset($episodes) && count($episodes) > 0)
    
        @foreach($episodes as $episode)
            <div class="episode">
                
                <h3>{{ $episode['name'] ?? 'No Title' }}</h3>
                @if(isset($episode['images'][0]['url']))
                    <img src="{{ $episode['images'][0]['url'] }}" alt="Episode Image">
                @endif
                @if(isset($episode['image_url']))
                    <img src="{{ $episode['image_url'] }}" alt="Episode Image">
                @endif
                <p><strong>Duration:</strong> {{ gmdate('H:i:s', ($episode['duration_ms'] ?? 0) / 1000) }}</p>
                <p><strong>Explicit:</strong> {{ $episode['explicit'] ? 'Yes' : 'No' }}</p>
                <p><strong>Description:</strong> {{ $episode['description'] ?? 'No Description Available' }}</p>
                @if(isset($episode['external_urls']['spotify']))
                    <a href="{{ $episode['external_urls']['spotify'] }}" target="_blank">Listen on Spotify</a>
                @endif
                @php
                    $existingEpisode = $existingEpisodes[$episode['id']] ?? null;
                @endphp
                @if ($existingEpisode)
                <p>Added by: {{ $existingEpisode->user->name }}</p>
                <p><a href="/podcast/episode/{{ $episode['id'] }}"><button>View Episode</button></a></p> 
                @else (!isset($episode['spotify_id']))
                <p><a href="/podcast/episode/{{ $episode['id'] }}">Add To Database</a></p> 
                @endif
            </div>
        @endforeach
    @elseif(isset($errorMessage))
        <p>{{ $errorMessage }}</p>
    @else
        <p>No results found.</p>
    @endif
    </x-simple-content-wrapper>
    
  



</x-app-layout>

