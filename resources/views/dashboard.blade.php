<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>

    <x-simple-content-wrapper>
                    Welcome: {{ $userInfo }}
    </x-simple-content-wrapper>

    <x-simple-content-wrapper>
                    <h2>Episodes Added</h2>
                    @if (!empty($episodes))
                        @foreach ($episodes as $episode)
                            <div>
                                <h3>{{ $episode['name'] }}</h3>
                            </div>
                        @endforeach
                    @else
                        <p>No episodes found.</p>
                    @endif
    </x-simple-content-wrapper>

    <x-simple-content-wrapper>
                    <h2>Summaries</h2>
                    @if (!empty($episodes))
                        @foreach ($summaries as $summary)
                            <div>
                                <h2>Chunk {{ $summary->Tchunks->title  }} {{ $summary->Tchunks->Transcript->episode_title }}</h2>
                                <h3>{{ $summary['summary_text'] }}</h3>
                                <br/>
                            </div>
                        @endforeach
                    @else
                        <p>No summaries found.</p>
                    @endif
    </x-simple-content-wrapper>
    
</x-app-layout>
