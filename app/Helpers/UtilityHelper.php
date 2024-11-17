<?php

namespace App\Helpers;

use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class UtilityHelper
{
    public static function validEmail($email)
    {
        // First, we check that there's one @ symbol, and that the lengths are right
        if (!preg_match('/^[^@]{1,64}@[^@]{1,255}$/', $email)) {
            // Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
            return false;
        }
        // Split it into sections to make life easier
        $email_array = explode('@', $email);
        $local_array = explode('.', $email_array[0]);
        for ($i = 0; $i < sizeof($local_array); $i++) {
            if (!preg_match("/^(([A-Za-z0-9!#$%&'*+\/=?^_`{|}~-][A-Za-z0-9!#$%&'*+\/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$/", $local_array[$i])) {
                return false;
            }
        }
        if (!preg_match("/^\[?[0-9\.]+\]?$/", $email_array[1])) { // Check if domain is IP. If not, it should be valid domain name
            $domain_array = explode('.', $email_array[1]);
            if (sizeof($domain_array) < 2) {
                return false; // Not enough parts to domain
            }
            for ($i = 0; $i < sizeof($domain_array); $i++) {
                if (!preg_match('/^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$/', $domain_array[$i])) {
                    return false;
                }
            }
        }

        return true;
    }

    public static function sanitizeUrl(string $url): string
    {
        if (Str::substr($url, -1) === '/') {
            return substr($url, 0, -1);
        }

        return $url;
    }

    public static function getColumName($iso, $fieldName)
    {
        try {
            if ($iso == 'en') {
                $columName = $fieldName;
            } else {
                $columName = $iso;
                if ($columName == trim($columName) && strpos($columName, ' ') !== false) {
                    $columName = str_replace(' ', '_', $columName);
                }
                if ($columName == trim($columName) && strpos($columName, '-') !== false) {
                    $columName = str_replace('-', '_', $columName);
                }
                $columName = $columName.'_'.$fieldName;
            }

            return $columName;
        } catch (Exception $e) {
            return $fieldName;
        }
    }

    public static function getLabelName($name, $labelName)
    {
        try {
            return $name.' '.$labelName;
        } catch (Exception $e) {
            return $labelName;
        }
    }

    public static function logError($exception)
    {
        $userId = (Auth::id()) ? Auth::id() : null;
        $route = request()->path();
        $ip = request()->ip();
        $time = now();
        $file = $exception->getFile();
        $line = $exception->getLine();

        Log::channel('database')->error($exception->getMessage(), [
            'exception' => $exception,
            'user_id'   => $userId,
            'route'     => $route,
            'ip'        => $ip,
            'time'      => $time,
            'file'      => $file,
            'line'      => $line,
        ]);
    }

    public static function isEngLocale(): bool
    {
        return app()->getLocale() === 'en';
    }

    public static function generateURL($component, $slug)
    {
        try {
            $frontEndUrl = self::sanitizeUrl(config('site-settings.frontend_site_url'));
            $componentFrontEndUrl = sprintf('%s/'.$component.'/%s', $frontEndUrl, $slug);

            return $componentFrontEndUrl;
        } catch (\Exception $e) {
            UtilityHelper::logError($e);

            return false;
        }
    }
}
