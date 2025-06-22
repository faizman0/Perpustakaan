<?php

namespace App\Helpers;

class RouteHelper
{
    public static function getRoutePrefix()
    {
        if (auth()->user()->hasRole('admin')) {
            return 'admin.';
        }
        return 'petugas.';
    }

    public static function route($name, $parameters = [], $absolute = true)
    {
        $prefix = self::getRoutePrefix();
        return route($prefix . $name, $parameters, $absolute);
    }

    public static function url($path)
    {
        if (auth()->user()->hasRole('admin')) {
            return '/admin' . $path;
        }
        return '/petugas' . $path;
    }
} 