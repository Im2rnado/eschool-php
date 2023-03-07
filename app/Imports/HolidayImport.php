<?php

namespace App\Imports;

use App\Models\Holiday;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Throwable;

class HolidayImport implements ToCollection, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function  __construct() {}
    public function collection(Collection $rows)
    {
        $validator = Validator::make($rows->toArray(), [
            '*.date' => 'required|date',
            '*.title' => 'required',
            '*.description' => 'nullable',
        ]);

        $validator->validate();
        if ($validator->fails()) {
            $response = array(
                'error' => true,
                'message' => $validator->errors()->first()
            );
            return response()->json($response);
        }

        foreach ($rows as $row) {
            try {
                $holiday = new Holiday();
                $holiday->date = date('Y-m-d', strtotime($request->date));
                $holiday->title = $request->title;
                $holiday->description = $request->description;
                $holiday->save();
                $response = array(
                    'error' => false,
                    'message' => trans('data_store_successfully')
                );
            } catch (\Throwable $e) {
                $response = array(
                    'error' => true,
                    'message' => trans('error_occurred')
                );
            }
            return response()->json($response);
        }
    }
}
