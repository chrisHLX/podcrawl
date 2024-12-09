<x-app-layout>
    
        @foreach($shows as $show)
        <div class="episode">
            <p><strong>Show Name: {{ $show->name }}</strong></p>
            <img src='{{ $show->image_url }}' alt='{{ $show->name }}'></img>
            <p><strong>Publisher: <a href='podcast/showsID/{{ $show->spotify_id }}'>{{ $show->publisher }}</a></strong></p>
            <p><strong>Description: {{ $show->description }}</strong></p>
            <p><strong>Listen on Spotify: <a href='{{ $show->spotify_url }}' target='_blank'> {{ $show->name }}</a></strong></p>      
        </div>
        @endforeach

</x-app-layout>