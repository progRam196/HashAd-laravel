<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Crypt;

use JWTAuth;


class Hashtags extends JsonResource
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
            'id'=>Crypt::encryptString($this->id),
            'hashtag'=>$this->hashtag,
            'count'=>$this->count,
            'subscriber_count'=>$this->subscriber_count,
            'subscribedStatus'=>$this->checkStatus($this->subscribedUser)
        ];
    }

    public function checkStatus($array)
    {
        if(count($array) > 0 )
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }
}
