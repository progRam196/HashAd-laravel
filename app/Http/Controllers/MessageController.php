<?php

namespace App\Http\Controllers;

use App\Message;
use Illuminate\Http\Request;

use JWTAuth;
use DB;
use Illuminate\Support\Facades\Crypt;

use App\Http\Resources\Messages as MessageResource;


class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $user = JWTAuth::User();    
        $decryptedID = $user['id'];

        $messages = Message::where(function ($query) use ($decryptedID) {
            $query->where('messages.user_id', '=', $decryptedID)
                  ->where('conversation_initiate','=',1)
                  ->whereRaw("NOT find_in_set($decryptedID,conversation_deleted_users)");
        })->orWhere(function ($query) use ($decryptedID) {
            $query->where('messages.sender_id', '=', $decryptedID)
                  ->where('conversation_initiate','=',1)
                  ->whereRaw("NOT find_in_set($decryptedID,conversation_deleted_users)");
        })->get();

        return MessageResource::collection($messages);

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

        $validator=$request->validate([
            "ad_id" => 'required',
            "user_id" => 'required',
            'message'=> 'required'
        ]);
        $user = JWTAuth::User();    
        $sessionID = $user['id'];
        $decryptedADID = Crypt::decryptString($requestData['ad_id']);
        $decryptedID = Crypt::decryptString($requestData['user_id']);
        $requestData['ad_id'] = $decryptedADID;
        $requestData['sender_id'] = $sessionID;
        $requestData['user_id'] = $decryptedID;

        $requestData['conversation_key'] = $this->generateConvKey($requestData['user_id'],$requestData['sender_id'],$decryptedADID);
        $status = isset($requestData['status'])?$requestData['status']:2;
        $requestData['conversation_initiate'] = $status;
        $requestData['conversation_deleted_users'] = '';
        $messages = Message::where(function ($query) use ($requestData) {
            $query->where('conversation_key', '=', $requestData['conversation_key'])
                  ->Where('conversation_initiate', '=', 1);
        })->get();

        if(count($messages) == 0 && $status == 1)
        {
            unset($requestData['status']);
            Message::create($requestData);
        }
        else {
            Message::create($requestData);
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
     * @param  \App\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $decryptedID = Crypt::decryptString($id);
        $user = JWTAuth::User();    
        $sessionID = $user['id'];

        $messages = Message::where([['messages.conversation_key', '=', $decryptedID],
        ['conversation_initiate', '=', 2]])->whereRaw("NOT find_in_set($sessionID,conversation_deleted_users)")
        ->get();

        return MessageResource::collection($messages);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function edit(Message $message)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Message $message)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $decryptedID = Crypt::decryptString($id);
        $user = JWTAuth::User();    
        $sessionID = $user['id'];
      //  $requested_data = ['conversation_deleted_users'=>$sessionID]; 
        $user = Message::where([['conversation_key','=', $decryptedID],['conversation_initiate','=', 1]])->first();

        $concat = $user->conversation_deleted_users.",".$sessionID;
        $requested_data = ['conversation_deleted_users'=>$concat];
        $user->update($requested_data);
        return $user;
    }

    public function generateConvKey($user_id,$sender_id,$ad_id)
    {
        $users = [$user_id,$sender_id];
        sort($users);
        $users = implode('USERS_',$users);
        return "AD".$ad_id.$users;
        

    }
}
