<?php

namespace API\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use API\User;

class UserController extends Controller
{
    public function show($id)
    {
        try {
            $user = User::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json(['status' => 'fail', 'data' => ['SQLSTATE' => $e->getMessage()]], 400);
        }
        if (count($user) === 0) {
            return response()->json(['message' => 'error'], 404);
        }
        return response()->json(['status' => 'success', 'data' => ['user' => $user]], 200);
    }

    public function store(Request $request)
    {
        try {
            $data = $request->only('first_name', 'last_name', 'email', 'password');
            $data['password'] = Hash::make($data['password']);
            User::create($data);
        } catch (QueryException $e) {
            return response()->json(['status' => 'fail', 'data' => ['SQLSTATE' => $e->getCode()]], 400);
        }
        return response()->json(['message' => 'success'], 201);
    }

    public function update(Request $request, $id)
    {
        try {
            $data = $request->all();
            $data['password'] = Hash::make($data['password']);
            $user = User::findOrFail($id);
            $user->fill($data)->save();
        } catch (ModelNotFoundException $e) {
            return response()->json(['status' => 'fail', 'data' => ['SQLSTATE' => $e->getMessage()]], 400);
        }
        return response()->json(['message' => 'success'], 200);
    }

    public function delete($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
        } catch (ModelNotFoundException $e) {
            return response()->json(['status' => 'fail', 'data' => ['SQLSTATE' => $e->getMessage()]], 404);
        }
        return response()->json(['message' => 'success'], 200);
    }
}
