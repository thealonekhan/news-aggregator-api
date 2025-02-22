<?php

namespace App\Repositories;

use App\Models\Article;

class ArticleRepository
{
    public function storeArticle($data)
    {
        return Article::updateOrCreate(
            ['url' => $data['url']],
            $data
        );
    }

    public function getArticles($filters = [])
    {
        $query = Article::query();

        $query->when(!empty($filters['search']), function ($q) use ($filters) {
            $q->where(function ($subQuery) use ($filters) {
                $subQuery->where('title', 'LIKE', "%{$filters['search']}%")
                         ->orWhere('content', 'LIKE', "%{$filters['search']}%");
            });
        });

        if (!empty($filters['category'])) {
            $query->where('category', $filters['category']);
        }

        if (!empty($filters['source'])) {
            $query->where('source', $filters['source']);
        }

        if (!empty($filters['author'])) {
            $query->where('author', $filters['author']);
        }

        if (!empty($filters['date'])) {
            $query->whereDate('published_at', $filters['date']);
        }

        return $query->paginate(10);
    }
}