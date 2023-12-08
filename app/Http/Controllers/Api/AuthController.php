<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Utils\ResponseCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    //
    public function registerUser(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8'
        ]);

        if($validator->fails()){
            return ResponseCode::errorPost($validator->errors());
        }

        try{
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'role' => 'user',
                'password' => Hash::make($request->password)
            ]);

            $token = $user->createToken('authToken')->plainTextToken;

            $data = [
                'user' => $user,
                'token' => $token
            ];
            return ResponseCode::successPost('Successfully Register Account', $data);
        }catch(\Exception $e){
            return ResponseCode::errorPost($e->getMessage());
        }
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(),[
            'email' => 'required|string|email',
            'password' => 'required|string|min:8'
        ]);

        if($validator->fails()){
            return ResponseCode::errorPost($validator->errors());
        }

        try{
            $user = User::where('email', $request->email)->first();

            if(!$user || !Hash::check($request->password, $user->password)){
                return ResponseCode::errorPost('The provided credentials are incorrect');
            }

            $token = $user->createToken($user['email'], [$user['role']])->plainTextToken;

            $data = [
                'user' => $user,
                'token' => $token
            ];
            return ResponseCode::successPost('Successfully Login', $data);
        }catch(\Exception $e){
            return ResponseCode::errorPost($e->getMessage());
        }
    }
}
