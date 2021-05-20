<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;

class MainController extends Controller
{
    public function register(Request $request){
    	$request->validate([
    		'name'=>'required|string',
    		'email'=>'required|string|email|unique:users',
    		'password'=>'required|string|confirmed']);
    	$user=new User([
    		'name'=>$request->name,
    		'email'=>$request->email,
    		'password'=>bcrypt($request->password)
    	]);
    	$user->save();
    	return response()->json([
    		'message'=>'Hello'],201);
    }

    public function login(Request $request){
    	$request->validate([
    		'email'=>'required|string|email',
    		'password'=>'required|string']);
    	$userCredentials = request(['email', 'password']);
    	if(!Auth::attempt($userCredentials))
    	{return response()->json([
    			'message'=>'Unauthorised'],401);}
    	else{$user=$request->user();
         $tokenResult=$user->createToken('User Personal');
         $token=$tokenResult->token;
         if ($request->remember_me) {
         	$token->expires_at= Carbon::now()->addDays(10);  
         }
        $token->save();
        return response()->json([
        	'access_token'=>$tokenResult->accessToken,
        	'token_type'=>'Bearer',
        	'expires_at'=>Carbon::parse($tokenResult->token->expires_at)->toDateString()
		]);
        }
    	}
    	
    public function logout(Request $request){
   	    $request->user()->token()->revoke();
   	    return response()->json([
    		'message'=>'logged out']);
  
    } 

    public function profile(Request $request){
    	return response()->json($request->user());
    }
}
