<?php

namespace API\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use API\WorkoutPlan;

class WorkoutPlanController extends Controller
{
    public function workoutPlanByUser($id)
    {
        $workoutPlanByUser = DB::table('workout_plan AS wkP')
            ->join('workout_type AS wkT', 'wkT.id_workout_type', '=', 'wkP.fk_workout_type')
            ->join('user AS u', 'wkP.fk_user', '=', 'u.id_user')
            ->where('wkP.fk_user', $id)
            ->select('u.id_user', 'u.first_name as user_name', 'u.email AS user_email',
                'wkT.name AS workout_type', 'wkT.description AS workout_type_description',
                'wkP.date AS workout_plan_date')
            ->distinct('u.first_name')
            ->get();

        if (count($workoutPlanByUser) === 0) {
            return response()->json(['message' => 'error'], 205);
        }

        return response()->json(['status' => 'success', 'data' => ['workoutPlanByUser' => $workoutPlanByUser]], 200);
    }

    public function store(Request $request)
    {
        $data = $request->only('fk_workout_type', 'fk_user', 'date');

        $workoutPlan = WorkoutPlan::create($data);

        if ($workoutPlan) {
            return response()->json(['message' => 'success'], 200);
        }
        return response()->json(['message' => 'error'], 500);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();

        $workoutPlan = WorkoutPlan::findOrFail($id);
        $workoutPlan->fill($data)->save();

        if ($workoutPlan) {
            return response()->json(['message' => 'success'], 200);
        }
        return response()->json(['message' => 'error'], 500);
    }

    public function delete($id)
    {
        $workoutPlan = WorkoutPlan::findOrFail($id);

        $workoutPlan->delete();

        if ($workoutPlan) {
            return response()->json(['message' => 'success'], 200);
        }
        return response()->json(['message' => 'error'], 500);
    }
}