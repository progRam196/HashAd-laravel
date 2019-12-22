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
            'id'=>Crypt::encryptString($this->id),
            'user_id'=>Crypt::encryptString($this->user_id),
            'ad_id'=>Crypt::encryptString($this->ad_id),
            'notification_type'=>$this->notification_type,
            'username'=>$this->username,
            //'show_text'=>$this->show_text,
            //'ad_image_1'=>$this->ad_image_exists($this->ad_image_1),
            'profile_image'=>$this->user_image_exists($this->profile_image),
            'notify_message'=>$this->notify_message($this->notification_type,$this->username)
        ];
    }

    public function notify_message($notify_type,$username)
    {
        switch($notify_type)
        {
            case 1:
            $notifyMessage = ' follows you';
            break;
            case 2:
            $notifyMessage = ' unfollows you';
            break;
            case 3:
            $notifyMessage = ' favourites your ad';
            break;
            case 4:
            $notifyMessage = ' unfavourites your ad';
            break;
            case 5:
            $notifyMessage = 'Subscriptions for Hashtags ';
            break;
            default:
            $notifyMessage="no message";
            break;
        }

        return $notifyMessage;
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
}
