<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Notifications\Notifiable;


class Setting extends Model
{
    //
    use Notifiable;

    protected $fillable = [
        'home_page_title', 'home_page_description', 'banner_image_1', 'banner_image_2','banner_image_3','banner_image_4' ];
} 
