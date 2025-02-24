<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use App\Http\Requests\GetArticlesRequest;
use App\Http\Resources\ArticleResourceCollection;
use App\Repositories\ArticleRepository;
use App\Services\NewsService;


class ArticleController extends Controller
{
    protected $articleRepository;
    protected $newsService;

    /**
     * ArticleController Constructor
     *
     * Initializes the controller with necessary dependencies.
     *
     * @param ArticleRepository $articleRepository Handles database operations for articles.
     * @param NewsService $newsService Fetches news articles from external APIs.
     */
    public function __construct(ArticleRepository $articleRepository, NewsService $newsService)
    {
        $this->articleRepository = $articleRepository;
        $this->newsService = $newsService;
    }

    /**
     * Fetch and store the latest news articles from external sources.
     *
     * This method triggers the NewsService to fetch articles from APIs
     * (e.g., NewsAPI, The Guardian, NYT) and store them in the database.
     *
     * @return JsonResponse Returns a JSON response indicating success or failure.
     */
    public function syncArticles(): JsonResponse
    {
        try {
            $this->newsService->fetchNews();
            return response()->json(['message' => 'News fetched successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch news', 'details' => $e->getMessage()], 500);
        }
    }

    /**
     * Retrieve filtered articles based on user queries.
     *
     * This method fetches articles from the database based on filters such as 
     * search query, category, source, author, and date. The results are paginated.
     *
     * @param GetArticlesRequest $request The validated request containing filter parameters.
     * 
     * @return JsonResponse Returns a JSON response with paginated articles and metadata.
     */
    public function getArticles(GetArticlesRequest $request): JsonResponse
    {
        try {
            $filters = $request->validated();
            $articles = $this->articleRepository->getArticles($filters);
            
            return response()->json(new ArticleResourceCollection($articles), 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch articles', 'details' => $e->getMessage()], 500);
        }
    }
    
}