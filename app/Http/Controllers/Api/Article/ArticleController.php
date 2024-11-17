<?php

namespace App\Http\Controllers\Api\Article;

use App\Helpers\UtilityHelper;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\Article\ArticleResource;
use App\Repositories\Api\Article\ArticleRepository;
use Illuminate\Http\Request;

class ArticleController extends AppBaseController
{   
    private ArticleRepository $articleRepository;

    public function __construct(articleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $articles = $this->articleRepository->getList($request);
            if ($articles !== false) {
                $response = [
                    'total_count'  => $articles->total(),
                    'per_page'     => $articles->perPage(),
                    'count'        => $articles->count(),
                    'current_page' => $articles->currentPage(),
                    'total_pages'  => $articles->lastPage(),
                    'list'         => ArticleResource::collection($articles),
                ];

                return $this->sendResponse($response, __('responses.article_list_found'));
            }

            return $this->sendError(__('responses.article_list_not_found'), 404);
        } catch (\Exception $e) {
            UtilityHelper::logError($e);
            return $this->sendError(__('responses.send_error'), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    { 
        try {
            $article=$this->articleRepository->getArticleBasedOnId($id);
            if($article){
                    return $this->sendResponse($article,__('responses.article_found'));
            }
            return $this->sendError(__('responses.not_found_article'), 404);

        } catch (\Exception $e) {
            UtilityHelper::logError($e);
            return $this->sendError(__('responses.send_error'), 500);
        }
        
    }
}
