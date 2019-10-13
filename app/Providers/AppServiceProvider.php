<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Image;
use Illuminate\Http\Resources\Json\Resource;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Resource::withoutWrapping();
        
        Validator::extend('base64image', function ($attribute, $value, $parameters, $validator) {
            if(is_array($value))
            {
                foreach($value as $k => $v)
                {
                    $explode = explode(',', $v);
                    $allow = ['png', 'jpg','jpeg', 'svg'];
                    $format = str_replace(
                        [
                            'data:image/',
                            ';',
                            'base64',
                        ],
                        [
                            '', '', '',
                        ],
                        $explode[0]
                    );
                    // check file format
                    if (!in_array($format, $allow)) {
                        return false;
                    }
                    // check base64 format
                    if (!preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $explode[1])) {
                        return false;
                    }
                }
            }
            else
            {
                $explode = explode(',', $value);
                $allow = ['png', 'jpg','jpeg', 'svg'];
                $format = str_replace(
                    [
                        'data:image/',
                        ';',
                        'base64',
                    ],
                    [
                        '', '', '',
                    ],
                    $explode[0]
                );
                // check file format
                if (!in_array($format, $allow)) {
                    return false;
                }
                // check base64 format
                if (!preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $explode[1])) {
                    return false;
                }
            }

            return true;
        });

        Validator::extend('base64dimension', function ($attribute, $value, $parameters, $validator) { 

            if(is_array($value))
            {
                foreach($value as $k => $v)
                {
                    $explode = explode(',', $v);            
                    $width = Image::make($v)->width();
                    $height = Image::make($v)->height();            
                    
                    if($width < 200 && $height < 200)
                    return false;
                    else
                    return true;
                }
            }
            else
            {
                $explode = explode(',', $value);            
                $width = Image::make($value)->width();
                $height = Image::make($value)->height();            
                
                if($width < 200 && $height < 200)
                return false;
                else
                return true;
            }

        });
    }
}
