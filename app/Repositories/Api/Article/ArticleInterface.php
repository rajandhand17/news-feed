<?php

namespace App\Repositories\Api\Article;

interface ArticleInterface
{
    public function getList($request);
    public function getArticleBasedOnId($id);
}