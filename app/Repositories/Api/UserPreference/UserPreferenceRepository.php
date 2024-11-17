<?php

namespace App\Repositories\Api\UserPreference;

use App\Services\UserPreferenceService;

class UserPreferenceRepository implements UserPreferenceInterface
{  
    protected $userPreferenceService;

    public function __construct(UserPreferenceService $userPreferenceService){
        $this->userPreferenceService=$userPreferenceService;
    }
    
    public function storeOrUpdate($request){
        try {
            return $this->userPreferenceService->storeOrUpdate($request);
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getUserPreferences($request){
        try {
            return $this->userPreferenceService->getUserPreferences($request);
        } catch (\Exception $e) {
            return false;
        }
    }
}