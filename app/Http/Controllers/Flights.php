<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\ProcessDjiFlightPlan;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;


class Flights extends Controller
{
    public function create(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|max:20480',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $file = $request->file('file');
        $diskName = 'local';

        try {
            $path = Storage::putFile($diskName, $file);

            if (!$path) {
                 throw new \Exception("Failed to store the uploaded file.");
            }

            ProcessDjiFlightPlan::dispatch([
                'path' => $path,
                'user_id' => $request->input('user_id')
            ]);

            return response()->json([
                'message' => 'Log file uploaded successfully and queued for processing.',
                'file_id' => $path
            ], 202);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}

