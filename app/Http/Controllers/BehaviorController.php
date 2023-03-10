<?php

namespace App\Http\Controllers;

use App\Models\Behavior;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BehaviorController extends Controller
{
    public function getBehaviorData(Request $request)
    {
        $response = Behavior::select('type')->where(['student_id' => $request->student_id, 'teacher_name' => $request->teacher_name])->pluck('type')->first();
        return response()->json($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        if (!Auth::user()->can('behavior-create') || !Auth::user()->can('behavior-edit')) {
            $response = array(
                'error' => true,
                'message' => trans('no_permission_message')
            );
            return response()->json($response);
        }

        $validator = Validator::make($request->all(), [
            'teacher_name' => 'required',
            'student_id' => 'required',
            'name' => 'required',
            'description' => 'required',
        ]);
        if ($validator->fails()) {
            $response = array(
                'error' => true,
                'message' => $validator->errors()->first()
            );
            return response()->json($response);
        }
        try {
            $student_id = $request->student_id;
            $teacher_name = $request->teacher_name;
            $name = $request->name;
            $description = $request->description;
            
            
            $behavior = new Behavior();

            $behavior->teacher_name = $teacher_name;
            $behavior->student_id = $student_id;
            $behavior->name = $name;
            $behavior->description = $description;

            $behavior->save();
            
            $response = [
                'error' => false,
                'message' => trans('data_store_successfully')
            ];
        } catch (Exception $e) {
            $response = array(
                'error' => true,
                'message' => trans('error_occurred'),
                'data' => $e
            );
        }
        return response()->json($response);
    }
}
