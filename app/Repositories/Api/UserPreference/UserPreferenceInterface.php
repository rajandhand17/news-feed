<?php

namespace App\Repositories\Api\UserPreference;

interface UserPreferenceInterface
{
    public function storeOrUpdate($request);
    public function getUserPreferences($request);
}