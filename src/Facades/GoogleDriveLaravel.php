<?php

namespace Smadia\LaravelGoogleDrive\Facades;

use Illuminate\Support\Facades\Facade;

class GoogleDriveLaravel extends Facade {

    public static function getFacadeAccessor()
    {
        return 'GDL';
    }

}