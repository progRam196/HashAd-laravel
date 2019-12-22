<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Notifications\Notifiable;


class Notification extends Model
{
    // 
    use Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ad_id','user_id','notify_user','hashtag_id','notification_type','notification_status'
    ];

    public function ad()
    {
        return $this->belongsTo('App\Ad');
    }

}
