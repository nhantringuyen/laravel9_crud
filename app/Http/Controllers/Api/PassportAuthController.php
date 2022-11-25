<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use http\Env\Response;
use Illuminate\Http\Request;
use App\Models\User;

class PassportAuthController extends Controller
{
    public function validatePhone($phone){
        if(preg_match(' /^((09[0-9]{8})|(01[0-9]{9})|(03[0-9]{8})|(07[0-9]{8})|(08[0-9]{8})|(05[0-9]{8}))$/',$phone)){
            return true;
        }else{
            return false;
        }
    }
    /**
     * Registration Req
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:4|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'password_confirmation' => 'required|min:8',
        ]);
        if($request->password != $request->password_confirmation){
            return response()->json(['error' => 'Please input password_confirmation same password'], 401);
        }
        if($request->phone != '' && !$this->validatePhone($request->phone)){
            return response()->json(['error' => 'Please input valid phone number'], 401);
        }
        $utype = $request->utype;
        if($utype == ''){
            $utype = 'USR';
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'utype'  => $utype,
            'phone'  => $request->phone,
            'address' => $request->address
        ]);
        return response()->json(['message' => 'register success'], 200);
    }

    /**
     * Login Req
     */
    public function login(Request $request)   {
        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];
        $remember = $request->remember;
        if($remember == '' || $remember == null){ $remember = false; }
        if (auth()->attempt($data,$remember)) {
            $token = auth()->user()->createToken('Laravel9PassportAuth')->accessToken;
            return response()->json(['token' => $token], 200);
        } else {
            return response()->json(['error' => "login failed"],401);
        }
    }

    public static function userInfo() {
        $user = auth()->user();
        return response()->json(['user' => $user], 200);
    }
}
