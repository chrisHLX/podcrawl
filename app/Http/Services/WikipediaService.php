<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Http;


class WikipediaService
{
    public function fetchPersonData($name)
    {
        // Wikipedia API URL to fetch more data: extracts, images, categories, links, and coordinates
        $url = "https://en.wikipedia.org/w/api.php?action=query&format=json&titles={$name}&prop=extracts|pageimages|categories|links|coordinates&exintro&explaintext&pithumbsize=500";

        // Specify the path to the CA certificate file
        $caCertPath = storage_path('cacert.pem');  // Adjust the path if needed

        // Make the request to Wikipedia with the CA certificate option
        $response = Http::withOptions([
            'verify' => $caCertPath,
        ])->get($url);

        if ($response->ok()) {
            $data = $response->json();
            $page = array_values($data['query']['pages'])[0];

            // Return more data from the page
            return [
                'title' => $page['title'] ?? null,
                'description' => $page['extract'] ?? null,
                'thumbnail' => $page['thumbnail']['source'] ?? null,
                'page_id' => $page['pageid'] ?? null,
                'categories' => $page['categories'] ?? null,
                'links' => $page['links'] ?? null,
                'coordinates' => $page['coordinates'] ?? null,
            ];
        }

        return null;
    }

}