<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ad;
use App\Hashtag;
use Image;
use JWTAuth;
use App\Http\Resources\Ads as AdResource;

class AdController extends Controller
{
    public $image_width = '1200' ;
    public $image_height = '1200' ;
    public $token = '1200' ;


    public function __construct(Request $request)
    {
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->token = $request->header('Authorization');
        $requestData = $request->all();

       
        if ($this->token != '') {      
            JWTAuth::setToken($this->token);
            $user = $this->fetch_jwt_details($this->token);
            $where = [
                ['user_id','NOT IN', $user['id']],
                ['ad_status','=', 'A'],
            ];
            if(count($requestData) > 0){
                
                $city = $requestData['city'];                    

                if($city != '')
                {
                   array_push($where,['city','=', $city]);
                }
                $hashtags = $requestData['hashtags'];
                if(count($hashtags) > 0)
                {
                    array_push($where,['hashtags','IN', $hashtags]);
                }
            }
        }
        else {
            $where = [
                ['ad_status','=', 'A'],
            ];
            if(count($requestData) > 0){
                
                $city = $requestData['city'];                    

                if($city != '')
                {
                   array_push($where,['city','=', $city]);
                }
                $hashtags = $requestData['hashtags'];
                if(count($hashtags) > 0)
                {
                    array_push($where,['hashtags','IN', $hashtags]);
                }
            }
        }
        $ads= Ad::where($where)->paginate(20);

        return AdResource::collection($ads);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'adtextarea' => 'required|max:255',
            'city' => 'required|max:50',
            'websitelink' =>'url|max:255',
        ]);
       
        $requestData = $request->all();
        $this->token = $request->header('Authorization');
        JWTAuth::setToken($this->token);
        $user = $this->fetch_jwt_details($this->token);
        $requestData['user_id']=$user['id'];
        $requestData['ad_text'] = $request->input('adtextarea');
        $requestData['show_text'] = $request->input('adtextarea');
        $coordinates = $request->input('coordinates');
        $requestData['coordinates'] = implode(",",$coordinates);

        $base64_str=$request->input('adImages');
    
        $requestData['ad_image_1'] = $this->base64ImageUpload((isset($base64_str[0])?$base64_str[0]:''),'ad_image_1');
        $requestData['ad_image_2'] = $this->base64ImageUpload((isset($base64_str[1])?$base64_str[1]:''),'ad_image_2');
        $requestData['ad_image_3'] = $this->base64ImageUpload((isset($base64_str[2])?$base64_str[2]:''),'ad_image_3');
        $requestData['ad_image_4'] = $this->base64ImageUpload((isset($base64_str[3])?$base64_str[3]:''),'ad_image_4');
        $requestData['show_text'] = $this->extractHashtags($requestData['adtextarea']);
        
        unset($requestData['adtextarea']);

        Ad::create($requestData);
        return ['message' => 'Ad Created!'];
 
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    public function update(Request $request, $id)
    {
        //
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

    public function base64ImageUpload($base64_str,$image_string)
    {
        if($base64_str != '')
        {
            $image = base64_decode($base64_str);
            $png_url = uniqid('AD-').time().".png";
            $path = public_path() . "/uploads/ad/" . $png_url;
            
            $img = Image::make($base64_str);
            $img->resize($this->image_width, $this->image_height, function ($constraint) {
            $constraint->aspectRatio();
            });
            $img->resizeCanvas($this->image_width, $this->image_height); 
            $img->save($path);
        }
        else {
         $png_url = '';
        }

        return $png_url;
    }

    public function fetch_jwt_details($token)
    {   
        return JWTAuth::toUser($token)->toArray();
    }

    public function extractHashtags($adtextarea)
    {
        preg_match_all('/#(\w+)/', $adtextarea, $matches);
        $adText = $adtextarea;
        $requestData['show_text'] = '';
        foreach ($matches[0] as $hashtag_name) {
         $adText = str_replace($hashtag_name,
            "<span class='hashtags'>".$hashtag_name."</span>",$adText);
        }
        foreach ($matches[1] as $hashtag_name) {
           Hashtag::create(['hashtag' => $hashtag_name]);
        }
        return $adText;
    }
}
