<x-app-layout>


    @foreach ($shows as $show)
            <div class="episode">
                
                <h3>Show Name: {{ $show['name'] ?? 'No Title' }}</h3>
                @if(isset($show['images'][0]['url']))
                    <img src="{{ $show['images'][0]['url'] }}" alt="show Image">
                @endif
                <p><strong>Publisher:</strong> {{ $show['publisher'] }}</p>
                <a href="{{ $show['external_urls']['spotify'] }}" target="_blank">Listen on Spotify</a>
                <p><strong>Explicit:</strong> {{ $show['explicit'] ? 'Yes' : 'No' }}</p>
                <p><strong>Description:</strong> {{ $show['description'] ?? 'No Description Available' }}</p>
                <p><strong>Current Total Episodes</strong> {{ $show['total_episodes'] }}</p>
                <p><strong>Show ID:</strong> {{ $show['id'] }}</p>
                <a href="/podcast/showsID/{{ $show['id'] }}" target="_blank">view show</a>
                
            </div>
        @endforeach
</x-app-layout>