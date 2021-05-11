<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function login(Request $request){
    
        if(!auth::attempt($request->only('email', 'password'))){
            throw new AuthenticationException();
        }
        
        return response(['token' => auth()->user()],200);
    }
    // public function logout(Request $request)
    // {
    //     Auth::logout();

    //     $request->session()->invalidate();

    //     $request->session()->regenerateToken();
    // }
    // public function login(Request $request){
    //     $rules =[
    //         'email' => 'required|email',
    //         'password'=> 'required|min:6'
    //     ];
    //     $validator = Validator::make($request->all(), $rules);
    //     if($validator->fails()){
    //         return response(["error" => "Wrong email and/or password"],404);
    //     }

    //     $user = User::where('email','=',$request->email)->firstOrFail();

    //     if(! Hash::check($request->password, $user->password)){
    //         return response(["error" => "Credentials do not match"], 404);
    //     }
    //     //dd($user->admin);
    //         $token = $user->createToken('token',['role' => $user->admin === true ? 'admin' : 'user']);
        
    //     return response(['token' => $token->plainTextToken], 200);
    // }
    // public function logout(Request $request){
    //     $request->user()->tokens()->delete();
    //     return response(["message" => "User logged out."]);
    // }
    public function info(Request $request){
            $user = $request->user();
            if($user->tokenCan('admin')){
                $user['scope'] = array('admin');
            }
            if($user->tokenCan('user')){
                $user['scope'] = array('user');
            }

        return response(['user' => $user]);
    }
}
