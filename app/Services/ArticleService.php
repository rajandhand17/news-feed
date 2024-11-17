<?php

namespace App\Services;

use App\Helpers\UtilityHelper;
use App\Models\Article;
use Exception;
class ArticleService
{   
    public function getList($request)
    {
        try {
            $article_list = Article::select();
            $article_list=self::filterArticleList($article_list,$request);
            return $article_list->paginate(10); // adding value of per page data.
            // Filter by keyword
            
        } catch (Exception $e) {
            UtilityHelper::logError($e);

            return false;
        }
    }

    public function filterArticleList($query,$request){
        try {
            if (isset($request->keyword) && $request->has('keyword')) {
                $query->where('title', 'like', '%' . $request->keyword . '%')
                      ->orWhere('description', 'like', '%' . $request->keyword . '%');
            }
    
            // Filter by date range
            if (isset($request->start_date) && isset($request->end_date) && $request->has('start_date') && $request->has('end_date')) {
                $query->whereBetween('published_at', [$request->start_date, $request->end_date]);
            }
    
            // Filter by category (Assuming a 'category' column exists)
            if (isset($request->category) && $request->has('category')) {
                $query->whereIn('category', $request->category);
            }
    
            // Filter by source (Assuming a 'source' column exists)
            if (isset($request->source) && $request->has('source')) {
                $query->where('source', $request->source);
            }
            return $query;
        } catch (Exception $e) {
            UtilityHelper::logError($e);
            return false;
        }
    }

    public function getArticleBasedOnId($id){
        try {
            return Article::find($id);
        } catch (Exception $e) {
            UtilityHelper::logError($e);
            return false;
        }
    }
}