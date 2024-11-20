<x-app-layout>

    <h1>{{ $person->name }}</h1>
    <p>Date of Birth: {{ $person->DOB }}</p>
    <p>Description: {{ $person->description }}</p>
    <p>Interests: {{ $person->interests }}</p>
    <a href="{{ $person->rss }}">RSS Feed</a>
</x-app-layout>
