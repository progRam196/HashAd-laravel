<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use JWTAuth;



class Hashtag extends Model
{
    use Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'hashtag','count','subscriber_count'
    ];

    public function subscribedUser()
    {
        $user = JWTAuth::user();
        return $this->hasMany('App\HashtagSubscriber','hashtag_id')->where('hashtag_subscribers.user_id','=',$user['id']);
    } 
}
