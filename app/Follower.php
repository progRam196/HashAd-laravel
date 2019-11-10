<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Notifications\Notifiable;


class Follower extends Model
{
    //
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
         'user_id', 'follower'
    ];

    public function userDetail()
    {
        return $this->hasOne('App\User','id','user_id');
    } 

    public function followerDetails()
    {
        return $this->hasOne('App\User','id','follower');
    } 



}
