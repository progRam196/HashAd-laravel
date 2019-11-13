<?php

namespace App\Http\Controllers;

use App\HashtagSubscriber;
use Illuminate\Http\Request;
use App\Http\Resources\HashtagSubscribers as SubscriberResource;

use DB;

use JWTAuth;

use Illuminate\Support\Facades\Crypt;


class HashtagSubscriberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $requestData = $request->all();

        if(isset($requestData['user_id']))
        {
        $decryptedID = Crypt::decryptString($requestData['user_id']);
        }
        else {
            $user = JWTAuth::User();    
            $decryptedID = $user['id'];
        }

        $subscribers = DB::table('hashtag_subscribers')
            ->join('users', 'users.id', '=', 'user_id')
            ->join('hashtags', 'hashtag_id', '=', 'hashtags.id')
            ->select('users.username','hashtag_id','user_id','hashtags.hashtag','users.profile_image')
            ->where('user_id','=',$decryptedID)
            ->get();

        return SubscriberResource::collection($subscribers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $requestData = $request->all();
        $user = JWTAuth::User();    
        $requestData['user_id']=$user['id'];
        $decryptedHashID = Crypt::decryptString($requestData['hashtag_id']);

        if($requestData['status'] == 0 )
        {
            unset($requestData['status']);
            $requestData['hashtag_id']=$decryptedHashID;
            HashtagSubscriber::create($requestData);
            DB::table('hashtags')->where('id', '=', $decryptedHashID)->increment('subscriber_count');

            return response([
                'message' => 'You have subscribed this hashtag'
            ], 200); 
        }
        else
        {
            HashtagSubscriber::where([['hashtag_id', '=', $decryptedHashID],['user_id', '=', $requestData['user_id']]])->delete();
            DB::table('hashtags')->where('id', '=', $decryptedHashID)->decrement('subscriber_count');

            return response([
                'message' => 'You have unsubscribed this hashtag'
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
     * @param  \App\HashtagSubscriber  $hashtagSubscriber
     * @return \Illuminate\Http\Response
     */
    public function show(HashtagSubscriber $hashtagSubscriber)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\HashtagSubscriber  $hashtagSubscriber
     * @return \Illuminate\Http\Response
     */
    public function edit(HashtagSubscriber $hashtagSubscriber)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\HashtagSubscriber  $hashtagSubscriber
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, HashtagSubscriber $hashtagSubscriber)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\HashtagSubscriber  $hashtagSubscriber
     * @return \Illuminate\Http\Response
     */
    public function destroy(HashtagSubscriber $hashtagSubscriber)
    {
        //
    }
}
