<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use League\Csv\Reader;

class StaffController extends Controller
{
    /**
     * Store a newly created resource in storage from up-load-csv.
     */
    public function upload(Request $request)
    {
        try{
            // 2MBまでのcsvのみを受け入れるバリデーション
            $request->validate([
                'csv_file' => 'required|mimes:csv',
            ]);

            // csvファイルを取得する．
            $file = $request->file('csv_file');

            // csvファイルを読み込む．
            $csvData = array_map('str_getcsv', file($file->getRealPath()));

            // 空もしくは一行のみのCSVファイルを検知．
            if (count($csvData) < 2) {
                return response()->json(['error' => 'The CSV file is empty or invalid.'], 400);
            }

            // ヘッダーを削除
            $headers = array_shift($csvData);

            // バリデーションの失敗したレコードと成功したレコードの配列を定義
            $errors = [];
            $successRecords = [];

            // 1レコードずつバリデーションを行い失敗のレコードと成功のレコードをそれぞれ配列に格納．
            foreach ($csvData as $index => $row) {
                $data = array_combine($headers, $row);

                $validator = Validator::make($data, [
                    'staff_id'    => 'required|string|unique:staff,staff_id',
                    'staff_name'  => 'required|string|max:255',
                    'department'  => 'required|string|max:255',
                    'gender'      => 'required|integer|in:1,2,3',
                    'start_date'  => 'required|date_format:Y-m-d',
                ]);

                // 失敗したレコードとエラーメッセージを保存
                if ($validator->fails()) {
                    $errors[] = [
                        'row' => $index + 2, // ヘッダーを除いた実際の行番号
                        'data' => $data,
                        'errors' => $validator->errors()->all()
                    ];
                    continue;
                }

                // 成功したレコードは配列に保存
                $successRecords[] = [
                    'staff_id'   => $data['staff_id'],
                    'staff_name' => $data['staff_name'],
                    'department' => $data['department'],
                    'gender'     => $data['gender'],
                    'start_date' => $data['start_date'],
                ];
            }

            // 成功したレコードのみデータベースに保存
            if (!empty($successRecords)) {
                Staff::insert($successRecords);
            }

            // 結果をJSONでレスポンス
            if (empty($errors)) {
                return response()->json([
                    'message' => 'CSV proccesing completed.',
                ], 201);
            } else {
                return response()->json([
                    'message' => 'validation error.',
                    'error_count' => count($errors),
                    'errors' => $errors
                ], 422);
            }
            return response()->json([
                
            ], 201);

        // 例外処理    
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStaffRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Staff $staff)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Staff $staff)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Staff $staff)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Staff $staff)
    {
        //
    }
}
