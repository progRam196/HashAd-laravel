<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Socialite;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'username';
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request) {

        $this->validateLogin($request);
        
        // try to auth and get the token using api authentication
        $credentials = request([$this->username(), 'password']);


        if (!$token = auth('api')->attempt($credentials)) {
            // if the credentials are wrong we send an unauthorized error in json format
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        return response()->json([
            'token' => $token,
            'type' => 'bearer', // you can ommit this
            'expires' => auth('api')->factory()->getTTL() * 60, // time to expiration
            
        ]);
    }

    // public function getSocialRedirect($account){
    //     try{
    //       return Socialite::with( $account )->redirect();
    //     }catch ( \InvalidArgumentException $e ){
    //       return redirect('/login');
    //     }
    //   }

    //   public function getSocialCallback( $account ){
    //     /*
    //       Grabs the user who authenticated via social account.
    //     */
    //     $socialUser = Socialite::with( $account )->user();
  
    //     /*
    //           Gets the user in our database where the provider ID
    //           returned matches a user we have stored.
    //       */
    //       $user = User::where( 'provider_id', '=', $socialUser->id )
    //                 ->where( 'provider', '=', $account )
    //                         ->first();
  
    //     /*
    //       Checks to see if a user exists. If not we need to create the
    //       user in the database before logging them in.
    //     */
    //     if( $user == null ){
    //       $newUser = new User();
  
    //       $newUser->name        = $socialUser->getName();
    //       $newUser->email       = $socialUser->getEmail() == '' ? '' : $socialUser->getEmail();
    //       $newUser->avatar      = $socialUser->getAvatar();
    //         $newUser->password    = '';
    //       $newUser->provider    = $account;
    //       $newUser->provider_id = $socialUser->getId();
  
    //       $newUser->save();
  
    //       $user = $newUser;
    //     }
  
    //     /*
    //       Log in the user
    //     */
    //     Auth::login( $user );
  
    //     /*
    //       Redirect to the app
    //     */
    //     return redirect('/');
    //   }
}
