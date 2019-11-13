<?php

namespace App\Http\Controllers;

use App\Follower;
use App\Notification;

use Illuminate\Http\Request;
use JWTAuth;
use DB;
use Illuminate\Support\Facades\Crypt;

use App\Http\Resources\Followers as FollowResource;


class FollowerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    { 
        $requestData = $request->all();

        if(isset($requestData['user_id']))
        {
        $decryptedID = Crypt::decryptString($requestData['user_id']);
        }
        else {
            $user = JWTAuth::User();    
            $decryptedID = $user['id'];
        }

        $followingUsers = DB::table('followers')
            ->join('users', 'users.id', '=', 'followers.user_id')
            ->select('users.username','user_id','follower', 'users.profile_image')
            ->where('follower','=',$decryptedID)
            ->get();
        $followerUsers = DB::table('followers')
        ->join('users', 'users.id', '=', 'followers.follower')
        ->select('users.username','user_id','follower', 'users.profile_image')
        ->where('user_id','=',$decryptedID)
        ->get();

        return ['followers'=>FollowResource::collection($followerUsers)
        ,'following'=>FollowResource::collection($followingUsers)];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
        $requestData = $request->all();
        $user = JWTAuth::User();    
        $requestData['follower']=$user['id'];
        $decryptedID = Crypt::decryptString($requestData['userid']);

        $requestData['user_id']=$decryptedID;
        if($requestData['status'] == 0 )
        {
            unset($requestData['status']);
            Follower::create($requestData);
            Notification::create([
                'user_id'=>$requestData['follower'],
                'ad_id'=>0,
                'notify_user'=>$decryptedID,
                'notification_type'=>1
            ]);
            return response([
                'message' => 'followed!'
            ], 200); 
        }
        else
        {
            Follower::where([['follower', '=', $requestData['follower']],['user_id', '=', $requestData['user_id']]])->delete();
            Notification::create([
                'user_id'=>$requestData['follower'],
                'ad_id'=>0,
                'notify_user'=>$decryptedID,
                'notification_type'=>2
            ]);
            return response([
                'message' => 'unfollowed'
            ], 200); 
        }
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
     * @param  \App\Follower  $follower
     * @return \Illuminate\Http\Response
     */
    public function show(Follower $follower)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Follower  $follower
     * @return \Illuminate\Http\Response
     */
    public function edit(Follower $follower)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Follower  $follower
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Follower $follower)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Follower  $follower
     * @return \Illuminate\Http\Response
     */
    public function destroy(Follower $follower)
    {
        //
    }
}
