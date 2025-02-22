<?php

return [
    'sources' => [
        'newsapi' => [
            'base_url' => env('NEWSAPI_URL', 'https://newsapi.org/v2/top-headlines'),
            'api_key'  => env('NEWSAPI_KEY'),
        ],
        'guardian' => [
            'base_url' => env('GUARDIAN_URL', 'https://content.guardianapis.com/search'),
            'api_key'  => env('GUARDIAN_KEY'),
        ],
        'nyt' => [
            'base_url' => env('NYT_URL', 'https://api.nytimes.com/svc/topstories/v2/home.json'),
            'api_key'  => env('NYT_KEY'),
        ],
    ],
];