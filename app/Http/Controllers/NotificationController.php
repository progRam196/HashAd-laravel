<?php

namespace App\Http\Controllers;

use App\Notification;
use Illuminate\Http\Request;

use JWTAuth;
use DB;
use Illuminate\Support\Facades\Crypt;

use App\Http\Resources\Notifications as NotifyResource;

class NotificationController extends Controller
{
    /** 
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = JWTAuth::User();    
        $decryptedID = $user['id'];
        $followingUsers = DB::table('notifications')
        ->join('users', 'users.id', '=', 'user_id')
        ->leftJoin('ads', 'ad.id', '=', 'ad_id')
        ->select('users.username','user_id','notification_type','users.profile_image','ad.ad_image_1','ad.show_text')
        ->where('notify_user','=',$decryptedID)
        ->get();
       return NotifyResource::collection($notifications);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function show(Notification $notification)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function edit(Notification $notification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Notification $notification)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function destroy(Notification $notification)
    {
        //
    }
}
