<x-app-layout>

    <!-- View for individual show page -->
 
            <div class="episode">
                
                <h3>Show Name: {{ $show->name }}</h3>
                <p>Show Description: {{ $show->description }}</p>
                <p>Show Publisher: {{ $show->publisher }}</p>
                <img src="{{ $show->image_url }}" alt="{{ $show->name }}"></img>
                <p>Listen on Spotify: <a href='{{ $show->spotify_url }}'>{{ $show->name }}</a></p>
                <p>Spotify ID: {{ $show->spotify_id }}</p>
               
           
            </div>
        
</x-app-layout>