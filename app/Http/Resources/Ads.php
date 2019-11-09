<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Crypt;
use JWTAuth;



class Ads extends Resource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        return [
            'id'=>Crypt::encryptString($this->id),
            'ad_text'=>$this->ad_text,
            'show_text'=>$this->show_text,
            'websitelink'=>$this->websitelink,
            'ad_image_1'=>$this->ad_image_exists($this->ad_image_1),
            'ad_image_2'=>$this->ad_image_exists($this->ad_image_2),
            'ad_image_3'=>$this->ad_image_exists($this->ad_image_3),
            'ad_image_4'=>$this->ad_image_exists($this->ad_image_4),
            'city'=>$this->city,
            'views'=>$this->views,
            'favCount'=>$this->favCount,
            'created_at' => $this->created_at,
            'created_at' => $this->created_at,
            'fav_status'=>$this->fav_status($this->favouritesCurrentUser),
            'fav_count'=>$this->fav_count($this->favouritesCurrentUser),
            'createUser'=>$this->createUser

        ];
    }

    public function ad_image_exists($photo)
     {
        if($photo != '')
        {
            if(file_exists( public_path() . '/uploads/ad/' . $photo)) {
                return url("uploads/ad/{$photo}");
            } else {
                return '';
            }
        }
        else
        {
            return '';
        }     
     }

     public function fav_status($favArray)
     {
        if(count($favArray) > 0 )
        return true;
        else
        return false;
     }

     public function fav_count($favArray)
     {
        return count($favArray);
     }
}


