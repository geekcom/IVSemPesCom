<?php

namespace API\Http\Controllers;

use Illuminate\Http\Request;
use API\WorkoutType;

class WorkoutTypeController extends Controller
{
    public function show($id)
    {
        $workoutType = WorkoutType::findOrFail($id);

        return response()->json(['status' => 'success', 'data' => ['workoutType' => $workoutType]], 200);
    }

    public function store(Request $request)
    {
        $data = $request->only('name', 'description');

        $workoutType = WorkoutType::create($data);

        if ($workoutType) {
            return response()->json(['message' => 'success'], 201);
        }
        return response()->json(['message' => 'error'], 500);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();

        $workoutType = WorkoutType::findOrFail($id);
        $workoutType->fill($data)->save();

        if ($workoutType) {
            return response()->json(['message' => 'success'], 200);
        }
        return response()->json(['message' => 'error'], 500);
    }

    public function delete($id)
    {
        $workoutType = WorkoutType::findOrFail($id);

        $workoutType->delete();

        if ($workoutType) {
            return response()->json(['message' => 'success'], 200);
        }
        return response()->json(['message' => 'error'], 500);
    }
}