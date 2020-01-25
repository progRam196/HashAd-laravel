<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use JWTAuth;

use Illuminate\Support\Facades\Crypt;

class Messages extends JsonResource
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
            'sender_id'=>Crypt::encryptString($this->sender_id),
            'message'=>$this->message,
            'conversation_key'=>Crypt::encryptString($this->conversation_key),
            'profile_image'=>$this->user_image_exists($this->user->profile_image),
            'sender_image'=>$this->user_image_exists($this->senderUser->profile_image),
            'created_at' => date("M d, h:i A",strtotime($this->created_at)),
            'ad_image_1'=>$this->ad_image_exists($this->ad->ad_image_1),
            'ad_id'=>Crypt::encryptString($this->ad->id),
            'ad_text'=>$this->ad->show_text,
            'hashtags'=>$this->ad->hashtags,
            'username'=>$this->user->username,
            'sender_name'=>$this->senderUser->username,
            'current_user_msg_status'=>$this->self_status($this->sender_id),
            'bgColor'=>$this->colorSelect($this->sender_id),
            'product_owner_status'=>$this->productOwnerStatus($this->ad->user_id)

        ];
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


    public function productOwnerStatus($current_userid)
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


    public function colorSelect($current_userid)
    {
        $user = JWTAuth::user();
        $user_id = $user['id'];
        if($current_userid == $user['id'])
        {
            return "#b8bbb8";
        }
        else {
            return "#e8e8e8";
        }
    }
    

    public function ad_image_exists($photo)
    {
       if($photo != '')
       {
        if(env('APP_ENV') != 'local')
        {
            $url = Storage::disk('s3')->url('ads/'.$photo);
            return $url;
        }
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
