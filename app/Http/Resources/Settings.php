<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Settings extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        return [
            'home_page_title'=>$this->home_page_title,
            'home_page_description'=>$this->home_page_description,
            'banner_image_1'=>$this->banner_image_exists($this->banner_image_1),
            'banner_image_2'=>$this->banner_image_exists($this->banner_image_2),
            'banner_image_3'=>$this->banner_image_exists($this->banner_image_3),
            'banner_image_4'=>$this->banner_image_exists($this->banner_image_4)

        ];
    }

    public function banner_image_exists($photo)
    {
       if($photo != '')
       {
           if(file_exists( public_path() . '/uploads/banners/' . $photo)) {
               return url("uploads/banners/{$photo}");
           } else {
               return '';
           }
       }
       else
       {
           return '';
       }     
    }
}
