<x-nav-link>
</x-nav-link>
This is shows </br>

@foreach ($Shows as $Show)
   <h1>Title: {{ $Show->title }}</h1>
</br>
   <h2>Description</h2>
   <p>{{ $Show->description }}</p>
   <form method="post" action="{{ route('saveDescription', $Show->id) }}" accept-charset="UTF-8">
                    {{ csrf_field() }}
                    <input type="text" name="description"></input>
                  
                    <button name="saveDescription">save</button>

   </form>
   
   @foreach ($Show->ratings as $rating)
        <p>Rating: {{ $rating->rating }}</p> <!-- Assuming 'value' is a column in the 'ratings' table -->
    @endforeach
</br>

</br>
@endforeach


