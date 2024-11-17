<?php

namespace App\Services;

use App\Models\UserPreference;

class UserPreferenceService
{   
    public function storeOrUpdate($request) 
    {
        try {
            $user = $request->user();
    
            // Ensure that required parameters exist in the request
            $sources = json_encode($request->input('sources', []));
            $categories = json_encode($request->input('categories', []));
            $authors = json_encode($request->input('authors', []));
            // Update or create preferences
            $preference = UserPreference::updateOrCreate(
                [
                    'user_id' => $user->id
                ],
                [
                    'sources' => $sources,
                    'categoies' => $categories,
                    'authors' => $authors,
                ]   
            );
        if($preference){
            return ['success' => true, 'user' => $user];
        }
        return false;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getUserPreferences($request){
        try {
            $userId=$request->user()->id;
            $getUserPreferences=UserPreference::where('user_id',$userId)->first();
            if($getUserPreferences){
                return $getUserPreferences;
            }
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }
}