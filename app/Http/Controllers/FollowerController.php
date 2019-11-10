<?php

namespace App\Http\Controllers;

use App\Follower;
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
        $user = JWTAuth::toUser($request->header('Authorization'));
        $user_id = $user['id'];
        $users = Follower::where('user_id','=',$user_id)->get();
        return  FollowResource::collection($users);
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
            return response([
                'message' => 'followed!'
            ], 200); 
        }
        else
        {
            Follower::where([['follower', '=', $requestData['follower']],['user_id', '=', $requestData['user_id']]])->delete();
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
