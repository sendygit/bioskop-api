<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => ['required'],
            'email' => ['required', ],
            'contact_phone' => ['required', 'numeric'],
            'password' => ['required',],
            'address' => ['required',]
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 
            Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'password' => bcrypt($request->password),
                'email' => $request->email,
                'contact_phone' => $request->contact_phone,
                'address' => $request->address,
                'is_active' => 1,
                'created_by' => 1,
                'update_by' => 1
            ]);
            $response = [
                'success' => true,
                'message' => 'Account created',
                'data' => $user
            ];

            return response()->json($response, Response::HTTP_CREATED);
        } catch (QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => "Failed" . $e->errorInfo
            ]);
            
        }
    }


    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('token')->plainTextToken;
            //JIKA ADA
            return response()->json([
                'success' => true,
                'message' => 'Berhasil Login',
                'user' => $user,
                'token' => $token,
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Email/Password Invalid'], 401);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil logout'
        ], 200);
    }
}
