<!-- resources/views/shows/high_rated.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>High Rated Shows</h1>

        @if($highRatedShows->isEmpty())
            <p>No shows have ratings above 9.</p>
        @else
            <ul>
                @foreach($highRatedShows as $show)
                    <li>
                        <strong>{{ $show->title }}</strong>
                        <ul>
                            @foreach($show->ratings as $rating)
                                <li>
                                    {{ $rating->user->name }} rated this {{ $rating->rating }}
                                    @if($rating->comment)
                                        <p>Comment: {{ $rating->comment }}</p>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
@endsection

