<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Repositories\ArticleRepository;
use App\Helpers\APIHelper;

class NewsService
{
    protected $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    public function fetchNews()
    {
        $sources = [
            'newsapi' => $this->fetchFromNewsAPI(),
            'guardian' => $this->fetchFromGuardian(),
            'nyt' => $this->fetchFromNYT(),
        ];

        foreach ($sources as $source => $articles) {
            foreach ($articles as $article) {
                $this->articleRepository->storeArticle($article);
            }
        }
    }

    private function fetchFromNewsAPI()
    {
        $config = config('newsapi.sources.newsapi');
        $response = Http::get($config['base_url'], [
            'apiKey' => $config['api_key'],
            'language' => 'en',
            'pageSize' => 10,
        ]);

        return $this->mapNewsAPIResponse($response->json());
    }

    private function fetchFromGuardian()
    {
        $config = config('newsapi.sources.guardian');
        $response = Http::get($config['base_url'], [
            'api-key' => $config['api_key'],
            'show-fields' => 'headline,bodyText',
            'page-size' => 10,
        ]);

        return $this->mapGuardianResponse($response->json());
    }

    private function fetchFromNYT()
    {
        $config = config('newsapi.sources.nyt');
        $response = Http::get($config['base_url'], [
            'api-key' => $config['api_key'],
        ]);

        return $this->mapNYTResponse($response->json());
    }

    private function mapNewsAPIResponse($data)
    {
        return collect($data['articles'] ?? [])->map(fn ($article) => [
            'title' => $article['title'],
            'content' => $article['content'],
            'source' => 'NewsAPI',
            'author' => $article['author'] ?? 'Unknown',
            'url' => $article['url'],
            'category' => $article['source']['name'] ?? 'General',
            'published_at' => APIHelper::convertToMySQLDatetime($article['publishedAt'] ?? now()),
        ])->toArray();
    }

    private function mapGuardianResponse($data)
    {
        return collect($data['response']['results'] ?? [])->map(fn ($article) => [
            'title' => $article['webTitle'],
            'content' => $article['fields']['bodyText'] ?? '',
            'source' => 'The Guardian',
            'author' => 'Unknown',
            'url' => $article['webUrl'],
            'category' => $article['sectionName'] ?? 'General',
            'published_at' => APIHelper::convertToMySQLDatetime($article['webPublicationDate'] ?? now()),
        ])->toArray();
    }

    private function mapNYTResponse($data)
    {
        return collect($data['results'] ?? [])->map(fn ($article) => [
            'title' => $article['title'],
            'content' => $article['abstract'],
            'source' => 'New York Times',
            'author' => $article['byline'] ?? 'Unknown',
            'url' => $article['url'],
            'category' => $article['section'] ?? 'General',
            'published_at' => APIHelper::convertToMySQLDatetime($article['published_date'] ?? now()),
        ])->toArray();
    }
}