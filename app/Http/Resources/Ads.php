<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;


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
            'ad_text'=>$this->ad_text,
            'show_text'=>$this->show_text,
            'ad_image_1'=>$this->ad_image_exists($this->ad_image_1),
            'ad_image_2'=>$this->ad_image_exists($this->ad_image_2),
            'ad_image_3'=>$this->ad_image_exists($this->ad_image_3),
            'ad_image_4'=>$this->ad_image_exists($this->ad_image_4),
            'city'=>$this->city,
            'views'=>$this->views,
            'favCount'=>$this->favCount,
            'created_at' => $this->created_at,
            'created_at' => $this->created_at,
        ];
    }

    public function ad_image_exists($photo)
     {
        if($photo != '')
        {
            if(file_exists( public_path() . '/uploads/ad/' . $photo)) {
                return url('uploads/ad/' . $photo);
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


