<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Users extends JsonResource
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
            'created_at'=>$this->create_at,
            'email'=>$this->email,
            'email_verified_at'=>$this->email_verified_at,
            'id'=>$this->id,
            'name'=>$this->name,
            'phone'=>$this->phone,
            'profile_image'=>$this->user_image_exists($this->profile_image),
            'updated_at'=>$this->updated_at,
            'user_status'=>$this->user_status,
            'user_type'=>$this->user_type,
            'username'=>$this->username,
            'business_name'=>$this->business_name,
            'business_address'=>$this->business_address,
            'business_description'=>$this->business_description,

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
