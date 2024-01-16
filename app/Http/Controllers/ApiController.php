<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\Rule;
use App\Models\User;
class ApiController extends Controller
{
    public function get_user()
    {
        
            $data = User::all();
           
            if($data){
    
           return  response()->json(['status'=>200,'data'=>$data,'message'=>"List of data"]);
            }else{
                return response()->json(['status'=>400,'data'=>$data,'message'=>"data not found"]);
            }
       
    }
    public function delete($id)
    {
        $data = User::findOrFail($id);
       
        $result = $data->delete();
        if($result){
           return  response()->json(['status'=>200,'data'=>$data,'message'=>"Record has been deleted"]);
        }
        else{
            return response()->json(['status'=>400,'data'=>$data,'message'=>"data not found"]);
        }
    }
    public function update(Request $request, $id)
    {
       
        $validatedData = $request->validate([
            'name' => ['required', 'string'],
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($id),
            ],
        
        ]);
     
        $user = User::findOrFail($id);
      
        $user->update($validatedData);
      
        return response()->json($user, Response::HTTP_ACCEPTED);
    }
}
