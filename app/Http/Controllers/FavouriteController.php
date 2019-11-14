<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Crypt;
use JWTAuth;


use App\Favourite;
use App\Notification;
use App\Ad;

use Illuminate\Http\Request;

class FavouriteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $decryptedADID = Crypt::decryptString($requestData['adid']);

        if($requestData['status'] == 0 )
        {
            unset($requestData['status']);
            $requestData['ad_id']=$decryptedADID;
            Favourite::create($requestData);
            $ad = Ad::findOrfail($decryptedADID);
           // $ad['user_id'];
            Notification::create([
                'user_id'=>$requestData['user_id'],
                'ad_id'=>$decryptedADID,
                'notify_user'=>$ad['user_id'],
                'notification_type'=>3
            ]);
            return response([
                'message' => 'Favourite is added'
            ], 200); 
        }
        else
        {
            Favourite::where([['ad_id', '=', $decryptedADID],['user_id', '=', $requestData['user_id']]])->delete();
            Notification::create([
                'user_id'=>$requestData['user_id'],
                'ad_id'=>$decryptedADID,
                'notify_user'=>0,
                'notification_type'=>4
            ]);
            return response([
                'message' => 'Favourite is removed'
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
     * @param  \App\Favourite  $favourite
     * @return \Illuminate\Http\Response
     */
    public function show(Favourite $favourite)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Favourite  $favourite
     * @return \Illuminate\Http\Response
     */
    public function edit(Favourite $favourite)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Favourite  $favourite
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Favourite $favourite)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Favourite  $favourite
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
      

    }
}
