<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Notifications\Notifiable;


class Message extends Model
{
    //
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ad_id', 'user_id', 'sender_id', 'message','conversation_key','conversation_initiate','conversation_deleted_users'
    ];



    public function user()
   {
      return $this->belongsTo('App\User');
   } 

   public function senderUser()
   {
      return $this->belongsTo('App\User','sender_id','id');
   } 

   public function ad()
   {
      return $this->belongsTo('App\Ad');
   } 
   



}
