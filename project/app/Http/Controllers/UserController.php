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
    	$validator = $request->validate([
            'username' => 'unique:users|required|min:6',
            'password' => 'required|min:6',
            'phone_number' => 'required|regex:/(01)[0-9]{9}/',
            'birthday' => 'required',
        ]);
        $user = User::create($request->all());

        return response()->json($user, 201);
	}

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $user->update($request->all());

        return response()->json($user, 200);
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        return response()->json(null, 204);
    }
}
