<?php

namespace App\Providers;
use App\Models\PodcastEpisode;
use App\Observers\PodcastEpisodeObserver;
use App\Models\Show;
use App\Observers\ShowObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //register the observer for episodes
        PodcastEpisode::observe(PodcastEpisodeObserver::class);
        Show::observe(ShowObserver::class);
        
    }
}
