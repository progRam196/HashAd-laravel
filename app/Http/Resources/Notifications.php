<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Crypt;


class Notifications extends JsonResource
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
            'user_id'=>Crypt::encryptString($this->user_id),
            'ad_id'=>Crypt::encryptString($this->ad_id),
            'notification_type'=>$this->notification_type
            //'profile_image'=>$this->user_image_exists($this->profile_image)
        ];
    }
}
