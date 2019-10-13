<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;



class Ad extends Model
{
    use Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'websitelink', 'city', 'ad_text' ,'show_text', 'ad_image_1' , 'ad_image_2', 'ad_image_3', 'ad_image_4' ,'coordinates' ,'ad_status','user_id'
    ];
    
}
