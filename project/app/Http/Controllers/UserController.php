<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input as Input;
use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    public function index()
    {
        return User::all();
    }
 
    public function show($username)
    {
        return User::where('username', $username)->first();
    }

    public function store(Request $request)
    {
    	$validator = \Validator::make($request->all(), [
	        'username' => 'unique:users|required|min:6',
            'password' => 'required|min:6',
            'phone_number' => 'required|regex:/(01)[0-9]{9}/',
            'birthday' => 'required',
	    ]);

		if ($validator->fails())
		{
			return response()->json($validator->errors(), 422);
		} else {
			$user = User::create($request->all());

	    	return response()->json($user, 201);
		}
        
	}

    public function update(Request $request, $username)
    {
    	$validator = \Validator::make($request->all(), [
            'password' => 'required|min:6',
            'phone_number' => 'required|regex:/(01)[0-9]{9}/',
            'birthday' => 'required',
	    ]);

	    if ($validator->fails())
		{
			return response()->json($validator->errors(), 422);
		} else {
			$user = User::where('username', $username)->first();
	        $current_password = $request->password;

			if ($user->password == $current_password) {

	        	$user->update($request->all());
	        	return response()->json($user, 200);
	        } else {
	        	return response()->json('You have entered wrong password. Please try agian.', 422);
	        }
		}

	    if ($request->request->all() == null) 
	    {
	    	return response()->json('Entered empty data.', 422);
	    }

    }

    public function delete(Request $request, $username)
    {
    	$validator = \Validator::make($request->all(), [
            'password' => 'required|min:6',
	    ]);

	    if ($validator->fails())
		{
			return response()->json($validator->errors(), 422);
		} else {

			$user = User::where('username', $username)->first();
	        $current_password = $request->input('password');

			if ($user->password == $current_password) 
			{

	        	$user->delete();
	        	return response()->json(null, 204);
	        } else {
	        	return response()->json('You have entered wrong password. Please try agian.', 422);
	        }
	    }

	    if ($request->request->all() == null) 
	    {
	    	return response()->json('Entered empty data.', 422);
	    }
    }

    public function saveAvatar(Request $request, $username) 
    {
        $validator = \Validator::make($request->all(), [
            'password' => 'required|min:6',
        ]);
        
        if ($validator->fails())
		{
			return response()->json($validator->errors(), 422);
		} else {
			$user = User::where('username', $username)->first();
	        if($request->password == $user->password) 
	        {
	            $avatarName = time().'.'.request()->avatar->getClientOriginalExtension();
	            request()->avatar->move(public_path('avatar'), $avatarName);
	            $user->avatar = $avatarName;
	        
	            $user->save();

	            return response()->json($user, 201);
	        } else {
	            return response()->json('error', 'You have entered wrong password. Please try agian.');
	        }
	    }

	    if ($request->request->all() == null) 
	    {
	    	return response()->json('Entered empty data.', 422);
	    }
    }
}
