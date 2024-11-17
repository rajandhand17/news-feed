<?php

namespace App\Http\Controllers\Api\UserPreference;

use App\Helpers\UtilityHelper;
use App\Http\Controllers\AppBaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserPreferenceRequest;
use App\Http\Resources\Article\ArticleResource;
use App\Http\Resources\UserPreference\UserPreferenceResource;
use App\Models\UserPreference;
use App\Repositories\Api\Article\ArticleRepository;
use App\Repositories\Api\UserPreference\UserPreferenceRepository;
use Illuminate\Http\Request;

class UserPreferenceController extends AppBaseController
{   
    private $userPreferenceRepository;
    private $articleRepository;

    public function __construct(UserPreferenceRepository $userPreferenceRepository,ArticleRepository $articleRepository)
    {
        $this->userPreferenceRepository = $userPreferenceRepository;
        $this->articleRepository=$articleRepository;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeOrUpdate(StoreUserPreferenceRequest $request)
    {
        try {
            $storeOrUpdate = $this->userPreferenceRepository->storeOrUpdate($request);
            if($storeOrUpdate){
                return $this->sendResponse(result: null, message: __('responses.store_update_success'), code: 200);
        }
        return $this->sendError(__('responses.store_update_failed'), 400);

        } catch (\Exception $e) {
            UtilityHelper::logError($e);
            return $this->sendError(__('responses.send_error'), 500);
        }
        
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {   try {
        $getPreferences=$this->userPreferenceRepository->getUserPreferences($request);
        
        if ($getPreferences) {
            return $this->sendResponse(result: UserPreferenceResource::make($getPreferences), message: __('responses.succcessfully_user_preferences'), code: 200);

        }
        return $this->sendError(__('responses.failed_user_preferences'), 500);
    } catch (\Exception $e) {
        UtilityHelper::logError($e);
        return $this->sendError(__('responses.send_error'), 500);
    }
    }

    public function personalizedFeed(Request $request){
        try {
            $getUserPreferences=$this->userPreferenceRepository->getUserPreferences($request);
            if(!empty($getUserPreferences)){
                $articles=$this->articleRepository->getList($getUserPreferences);
                if(!empty($articles)){
                    $response = [
                        'total_count'  => $articles->total(),
                        'per_page'     => $articles->perPage(),
                        'count'        => $articles->count(),
                        'current_page' => $articles->currentPage(),
                        'total_pages'  => $articles->lastPage(),
                        'list'         => ArticleResource::collection($articles),
                    ];
                    return $this->sendResponse($response,__('responses.article_list_found'));
                }
            }
        } catch (\Exception $e) {
            UtilityHelper::logError($e);
            return $this->sendError(__('responses.send_error'), 500);
        }
    }
}
