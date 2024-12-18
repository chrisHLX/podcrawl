<x-app-layout>
    @foreach ($episodes as $episode)
        <div class='episode'> 
            <p><h1>{{ $episode->show_name }}</h1></p>
            <p><strong>Find Show:</strong> <a href="/podcast/shows/{{ base64_encode($episode->show_name) }}">{{ $episode->show_name }}</a>
            <h3>{{ $episode->name }}</h3>
            <p><strong>Release Date:</strong> {{ $episode->release_date }}</p>
            <p><strong>Duration:</strong> {{ gmdate("H:i:s", $episode->duration_ms / 1000) }}</p>
            <p><strong>Description:</strong> {{ $episode->description }}</p>
            <p><strong>Language:</strong> {{ $episode->language }}</p>
            
            <p><strong>Listen on Spotify </strong><a href='{{ $episode->spotify_url }}'> {{ $episode->name }}</a></p>

            <img src="{{ $episode->image_url }}" alt="Episode Image" style="width:100px; height:auto;">
            <p><strong>Episode added by:</strong> {{ $episode->user->name }}</p>
        </div>
        <hr>
    @endforeach
</x-app-layout>
