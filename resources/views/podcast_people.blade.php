<x-app-layout>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" style="margin-top: 10px">
        <h1 class="block font-semibold text-xl text-gray-700">People Table</h1>
        @foreach ($peoples as $people)
        <div class='episode'>
            <p><span class="font-semibold">Name:</span> {{ $people['name'] }}</p>
            <p><span class="font-semibold">DOB:</span> {{ $people['DOB'] }}</p>
            <p><span class="font-semibold">RSS:</span> <a href='{{ $people['rss'] }}' target="_blank"> {{ $people['rss']}} </a></p>
            <p><span class="font-semibold">Field of interes:</span> {{ $people['interests'] }}</p>
            <p><span class="font-semibold">Views:</span> {{ $people['views'] }}</p>
            <p><span class="font-semibold">ID:</span> {{ $people['id'] }}</p>
            <a href="{{ route('person.showRequest', ['name' => $people->name]) }}">{{ $people->name }}</a>
            <br/>
        </div>
        @endforeach
        
    </div>

</x-app-layout>
