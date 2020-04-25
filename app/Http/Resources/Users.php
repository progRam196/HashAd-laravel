<?php

namespace App\Http\Resources;
use JWTAuth;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;


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
            'id'=>Crypt::encryptString($this->id),
            'name'=>$this->name,
            'phone'=>$this->phone,
            'profile_image'=>$this->user_image_exists($this->profile_image),
            'updated_at'=>$this->updated_at,
            'user_status'=>$this->user_status,
            'user_type'=>$this->check_user_type($this->user_type),
            'username'=>$this->username,
            'business_name'=>$this->business_name,
            'business_address'=>$this->business_address,
            'business_description'=>$this->business_description,
            'bio'=>$this->bio,
            'website_link'=>$this->website_link,
            //'ads'=>Ads::collection($this->ads),
            'selfStatus'=>$this->self_status($this->id),
            'follow_status'=>$this->follow_status($this->followersCurrentUser)

        ];
    }

    public function check_user_type($user_type)
    {
        if($user_type == 'N')
        {
            return false;
        }
        return true;

    }

    public function self_status($current_userid)
    {
        $user = JWTAuth::user();
        $user_id = $user['id'];
        if($current_userid == $user['id'])
        {
            return 1;
        }
        else {
            return 0;
        }
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

    public function follow_status($followArray)
    {
       if(count($followArray) > 0 )
       return true;
       else
       return false;
    }
}
