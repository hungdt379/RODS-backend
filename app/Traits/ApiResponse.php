<?php

namespace App\Traits;

trait ApiResponse
{
    public function successResponse($data, $message, $status = true)
    {
        if (is_null($data) || "" == $data)
            $data = new \stdClass();
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data,
            'ts' => time()
        ], 200);
    }

    public function successResponseWithPaging($data, $message, $pageIndex, $pageSize, $total, $status = true)
    {
        if (is_null($data) || "" == $data)
            $data = new \stdClass();
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data,
            'page_index' => (int)$pageIndex,
            'page_size' => (int)$pageSize,
            'total' => $total,
            'ts' => time()
        ], 200);
    }

    public function errorResponse($message, $data = null, $status = false, $errorCode)
    {
        if (is_null($data))
            $data = new \stdClass();
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data,
            'ts' => time()
        ], $errorCode);
    }
}
