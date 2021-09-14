<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class ProfileController extends Controller
{
    
    public function index()
    {
        $data = User::orderBy('created_at', 'ASC')->get();
        $response = [
            'message' => 'List profile',
            'data' => $data
        ];
        return response()->json($response, Response::HTTP_OK);
    }

   
    public function show($id_user)
    {
        $data = User::findOrFail($id_user);
        $response = [
            'message' => 'Detail of user',
            'data' => $data
        ];
         return response()->json($response, Response::HTTP_OK);
    }
       

   
    public function updateProfile(Request $request, $id_user)
    {
        $user = User::find($id_user)->update($request->all());

        $response = [
        'message' => 'Account updated',
        ];

        return response()->json($response, Response::HTTP_OK);
        
    //     $validator = Validator::make($request->all(),[
    //         'name' => ['required'],
    //         'email' => ['required'],
    //         'contact_phone' => ['required'],
    //         'address' => ['required']
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json($validator->errors(), 
    //         Response::HTTP_UNPROCESSABLE_ENTITY);
    //     }

    //     try {
    //         $user = User::findOrFail($id_user)->update([
    //             'name' => $request->name,
    //             'email' => $request->email,
    //             'contact_phone' => $request->contact_phone,
    //             'address' => $request->address,
    //         ]);
    //     $response = [
    //         'message' => 'Account updated'
    //         ];

    //     return response()->json($response, Response::HTTP_OK);           
    
    //     } catch (QueryException $e) {
    //         return response()->json([
    //             'message' => "Failed" . $e->errorInfo
    //         ]);
            
    //     }
     }

    public function updatePhoto(Request $request, $id_user)
    {
        try {
            $validator = Validator::make($request->all(),[
                'photo_profile' => ['required']
            ]);
        
            if ($validator->fails()) {
                return response()->json($validator->errors(), 
                Response::HTTP_UNPROCESSABLE_ENTITY);
            } else {
                $user = User::findOrFail($id_user);
                if ($request->photo_profile && $request->photo_profile->isValid()) {
                    $file_name = time().'.'.$request->photo_profile->extension();
                    $request->photo_profile->move(public_path('images'),$file_name);
                    $path = "public/images/$file_name";
                    $user->photo_profile = $path;
                }
                $user->update();
                $response = [
                    'message' => 'Photo updated',
                    'data' => $user
                    ];
                
                return response()->json($response, Response::HTTP_OK); 
                }

            }catch (QueryException $e) {
                    return response()->json([
                        'message' => "Failed" . $e->errorInfo
                    ]);
            }
        
    
    }
}


