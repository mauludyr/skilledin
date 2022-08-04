<?php

namespace App\Traits;

trait ResponseAPI
{
    public function mainResponse($message, $data = null, $statusCode, $isSuccess = true) {
        if(!$message) {
            return response()->json([
                "error" => true,
                "message" => "Message is required",
            ], 500);
        }

        if($isSuccess) {
            return response()->json([
                "message" => $message,
                "error" => false,
                "results" => $data,
            ], $statusCode);
        }
        else {
            return response()->json([
                "message" => $message,
                "error" => true,
            ], $statusCode);
        }
    }


    public function successResponse($message, $data = null, $statusCode = 200)
    {
        return $this->mainResponse($message, $data, $statusCode);
    }


    public function errorResponse($message, $statusCode)
    {
        return $this->mainResponse($message, null, $statusCode, false);
    }

    public function downloadResponse($filePath, $fileName)
    {
        $headers = [
            "Content-Type: application/json"
        ];

        return response()->download($filePath, $fileName, $headers);
    }
}
