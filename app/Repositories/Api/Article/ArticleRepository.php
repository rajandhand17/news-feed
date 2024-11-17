<?php

namespace App\Repositories\Api\Article;

use App\Helpers\UtilityHelper;
use App\Services\ArticleService;

class ArticleRepository implements ArticleInterface
{   
    private $articleService;

    public function __construct(ArticleService $articleService){
        $this->articleService=$articleService;
    }

    public function getList($request){
        try {
            return $this->articleService->getList($request);
        } catch (\Exception $e) {
            UtilityHelper::logError($e);

            return false;
        }
    }

    public function getArticleBasedOnId($id){
        try {
            return $this->articleService->getArticleBasedOnId($id);
        } catch (\Exception $e) {
            UtilityHelper::logError($e);
            return false;
        }
    }
}