<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use Illuminate\Support\Facades\Crypt;


class HashtagSubscribers extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
         return [
            'user_id'=>Crypt::encryptString($this->user_id),
            'hashtag_id'=>Crypt::encryptString($this->hashtag_id),
            'username'=>$this->username,
            'hashtag'=>$this->hashtag,
            'profile_image'=>$this->user_image_exists($this->profile_image)
        ];
    }

    public function user_image_exists($photo)
    {
       if($photo != '')
       {
        if(env('APP_ENV') != 'local')
        {
            $url = Storage::disk('s3')->url('users/'.$photo);
            return $url;
        }
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
