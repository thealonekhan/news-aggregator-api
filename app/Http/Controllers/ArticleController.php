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

    public function __construct(ArticleRepository $articleRepository, NewsService $newsService)
    {
        $this->articleRepository = $articleRepository;
        $this->newsService = $newsService;
    }

    public function fetchArticles(): JsonResponse
    {
        try {
            $this->newsService->fetchNews();
            return response()->json(['message' => 'News fetched successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch news', 'details' => $e->getMessage()], 500);
        }
    }

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