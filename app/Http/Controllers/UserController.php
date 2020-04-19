<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use JWTAuth;
use Image;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;


use App\Http\Resources\Users as userResource;


class UserController extends Controller
{
    public $image_width = '400' ;
    public $image_height = '400' ;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = JWTAuth::toUser($request->header('Authorization'));
        $user_id = $user['id'];
        $users = User::where('id',$user_id)->first();
        return new userResource($users);
    }

    public function verifyToken(Request $request)
    {
        if($request->header('Authorization') == '')
        {
            return response([
                'message' => 'Token not provided'
            ], 402); // Status code here
        }
        $user = JWTAuth::toUser($request->header('Authorization'));
        return $user;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $decryptedID = Crypt::decryptString($id);
        $users = User::where('id',$decryptedID)->first();
        return new userResource($users);
    }

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function userAds(Request $request)
    {
        $requestData = $request->all();

        $this->token = $request->header('Authorization');
    
        if ($this->token != '') {  
            $user = JWTAuth::toUser($this->token);    
            $where = [
                ['id','=', $user['id']],
            ];
        }
        $ads= User::where($where)->first();

        return new userResource($ads);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = JWTAuth::toUser($request->header('Authorization'));
        $id = $user['id'];
        $validator=$request->validate([
            "email" => 'required|unique:users,email,'.$id,
            "phone" => 'required|numeric|unique:users,phone,'.$id,
            //"profile_image"=>'base64image',
            "user_type"=>'required',
            "username"=>'required|unique:users,username,'.$id,
            'websitelink' =>'nullable|url|max:255',
            "business_name" => 'nullable|max:50',
            "business_address" => 'nullable|max:100',
            "business_description" => 'nullable|max:100',
            "country_code" => 'required|max:3'

        ]);
        $requested_data = $request->all(); 
        $user = User::findOrFail($id);
        $base64_str = $requested_data['profile_image'];
        $uploadImage = $this->base64ImageUpload($base64_str);
        if($uploadImage != '')
        { 
        $requested_data['profile_image'] = $uploadImage;
        }
        else {
        unset($requested_data['profile_image']);
        }
        $user->update($requested_data);
        return $user;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function fetch_jwt_details($token)
    {   
        return JWTAuth::toUser($token)->toArray();
    }

    public function base64ImageUpload($base64_str)
    {
        if($base64_str != '' && !(filter_var($base64_str, FILTER_VALIDATE_URL))) 
        {
            //$image = base64_decode($base64_str);
            // list($baseType, $image) = explode(';', $base64_str);
            // list(, $image) = explode(',', $image);
            // $image = base64_decode($image);
            $png_url = uniqid('PROFILE-').time().".png";
            $path =  public_path()."/uploads/users/" . $png_url;
            
            $img = Image::make($base64_str);
            $img->resize($this->image_width, $this->image_height, function ($constraint) {
            $constraint->aspectRatio();
            });
            $img->resizeCanvas($this->image_width, $this->image_height); 

            if(env('APP_ENV') == 'local')
            {
                $img->save($path);
            }
            else {
                $resized_image = $img->stream('png', 100);
                Storage::disk('s3')->put('users/'.$png_url,$resized_image);
            }
        }
        else {
         $png_url = '';
        }

        return $png_url;
    }
}
