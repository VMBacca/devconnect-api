<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', [
            'except'=>[
                'login', 
                'create', 
                'unauthorized'
                ]
        ]);
    }

    public function unauthorized(){
        return \response()->json(['error'=>'Unautorized'], 401);
    }

    public function login(Request $request){
        $array = ['error'=>''];

        $email = $request->input('email');
        $password = $request->input('password');
        
        if($email && $password){

            $token = Auth::guard('api')->attempt([
                'email'=>$email,
                'password'=>$password,
            ]);

            if(!$token){
                $array['error'] = 'Incorrect email or password';
                return $array;
            };

            $array['token'] = $token;
            return $array;
        }
        
        $array['error'] = 'Login data not sent';
        return $array;
    }

    public function logout(){
        Auth::guard('api')->logout();
        return ['error'=>''];
    }

    public function refresh()
{
    
    try {
        $guard = Auth::guard('api'); /** @var \Tymon\JWTAuth\JWTGuard $guard */
        $token = $guard->refresh();

        return [
            'error' => '',
            'token' => $token
        ];
    } catch (\Exception $e) {
        return [
            'error' => 'Invalid or expired token.'
        ];
    }
}

    public function create(Request $request){
        $array = ['error'=>''];

        $name = $request->input('name');
        $email = $request->input('email');
        $password = $request->input('password');
        $birthdate = $request->input('birthdate');

        if($name && $email && $password && $birthdate){
            // Validating the birthdate
            if(\strtotime($birthdate)=== false){
                $array['error'] = 'Invalid birthdate.';
                return $array;
            }

            // Verifying the existence of the email
            $emailExists = User::where('email', $email)->count();

            if($emailExists === 0){
                $hash = \password_hash($password, \PASSWORD_DEFAULT);

                $newUser = new User();
                $newUser->name = $name;
                $newUser->email = $email;
                $newUser->password = $hash;
                $newUser->birthdate = $birthdate;
                $newUser->save();

                $token = Auth::guard('api')->attempt([
                    'email'=>$email,
                    'password'=>$password,
                ]);

                if(!$token){
                    $array['error'] = 'An error occurred';
                    return $array;
                };

                $array['token'] = $token;
            }else{
                $array['error'] = 'Email already registered';
                return $array;
            }
        }else{
            $array['error'] = 'The user did not submit all completed fields.';
            return $array;
        }

        return $array;
    }
}
