<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
class ApiController extends Controller
{


    public function sendError($message, $errors = [], $code = 400)
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if (!empty($errors)) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  bcrypt($user->id);
        $success['name'] =  $user->name;

        return $this->sendResponse($success, 'User registered successfully.');
    }
    public function login(Request $request)
    {
     

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])) 
        {
    
        
             $user = Auth::user(); 
            $success['token'] =  $user->createToken('MyApp')->plainTextToken; 
    
         return response()->json(['status'=>200, 'success' => $success,'message'=>'Login Sucessfully']);
    
        }
        return response()->json(['error'=>'Unauthorised'], 401);
        
    }


    public function sendResponse($data, $message = 'Success', $code = 200)
    {
        $response = [
            'success' => true,
            'data' => $data,
            'message' => $message,
        ];

        return response()->json($response, $code);
    }
    public function get_user()
    {

       
        $user = User::get();

        if ($user) {
           
           return  response()->json(['status'=>200,'data'=>$user,'message'=>"List of data"]);
           
        }
        else{
            return response()->json(['status'=>400,'data'=>[],'message'=>"Please login"]);

        }
       
    }
    public function delete(Request $request)
    {
        $data = User::findOrFail($request->id);
       
        $result = $data->delete();
        if($result){
           return  response()->json(['status'=>200,'data'=>$data,'message'=>"Record has been deleted"]);
        }
        else{
            return response()->json(['status'=>400,'data'=>$data,'message'=>"data not found"]);
        }
    }
    public function update(Request $request)
    {

       
        $validatedData = $request->validate([
            'name' => ['required', 'string'],
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($request->id),
            ],
        
        ]);
     
        $user = User::findOrFail($request->id);
      
        $user->update($validatedData);
      
        return response()->json($user, Response::HTTP_ACCEPTED);
    }
}
