<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Crypt;


class Followers extends JsonResource
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
            'follower'=>Crypt::encryptString($this->follower),
            'username'=>$this->username,
            'profile_image'=>$this->user_image_exists($this->profile_image)
        //    'userDetails'=> new Users($this->userDetail),
        //     'followerDetails'=> new Users($this->followerDetails),
        ];
    }

    public function user_image_exists($photo)
    {
       if($photo != '')
       {
           if(file_exists( public_path() . '/uploads/users/' . $photo)) {
               return url("uploads/users/{$photo}");
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
