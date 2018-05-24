<?php

namespace App\Http\Controllers;

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
    	$validator = $this->validate($request, [
	        'username' => 'unique:users|required|min:6',
            'password' => 'required|min:6',
            'phone_number' => 'required|regex:/(01)[0-9]{9}/',
            'birthday' => 'required',
	    ]);
	    var_dump($validator); die();

	    if ($validator->fails())
		{
			return response()->json($validator->errors(), 422);
		} else {
			$user = User::where('username', $username)->first();

	        $user->update($request->all());

	        return response()->json($user, 200);
		}

    }

    public function delete(Request $request, $username)
    {
    	$validator = $this->validate($request, [
            'password' => 'required|min:6'
	    ]);

	    if ($validator->fails())
		{
			return response()->json($validator->errors(), 422);
		} else {

	        $user = User::where('username', $username)->first();

	        $user->delete();

        	return response()->json(null, 204);
	    }
    }

    public function saveAvatar(Request $request, $username) 
    {
        $validator = $request->validate([
            'password' => 'required|min:6',
        ]);

        $user = User::where('username', $username)->first();
        
        if ($validator->fails())
		{
			return response()->json($validator->errors(), 422);
		} else {
	        if($user && \Hash::check($request->password, $user->password)) {
	            $avatarName = time().'.'.request()->avatar->getClientOriginalExtension();
	            request()->avatar->move(public_path('avatar'), $avatarName);
	            $user->avatar = $avatarName;
	        
	            $user->save();

	            return response()->json($user, 201);
	        } else {
	            return back()->with('error', 'You have entered wrong password. Please try agian.');
	        }
	    }
    }
}
