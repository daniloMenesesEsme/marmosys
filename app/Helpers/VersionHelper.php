<?php

namespace App\Helpers;

class VersionHelper
{
    public static function getVersion()
    {
        $version = config('version');
        return sprintf(
            'v%s.%s.%s-%s',
            $version['major'],
            $version['minor'],
            $version['patch'],
            $version['release']
        );
    }
} 