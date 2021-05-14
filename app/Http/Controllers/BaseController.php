<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;
use Illuminate\Http\Response;

class BaseController extends Controller {

    //
    public $success = null;
    public $message = '';

    //

    /**
     * success response method.
     *
     * @param $result
     * @param $message
     * @return JsonResponse
     */
    public function sendResponse($result, $message) {
        $response = [
            'success' => true,
            'data' => $result,
            'message' => $message,
        ];
        return response()->json($response, 200);
    }

    /**
     * return error response.
     *
     * @param string $errorMessages
     * @param int $code
     * @return JsonResponse
     */
    public function sendError($errorMessages = '', $code = 404) {
        $response = [
            'message' => $errorMessages,
            'code' => $code
        ];
        return response()->json($response, 200);
    }

    /**
     * return json data
     * @param type $data
     * @return type
     */
    public function jsonResponse($data = array()) {
        $data = is_array($data) ? $data : array();
        if (!isset($data['success'])) {
            $data['success'] = $this->getSuccess();
        }
        if (!isset($data['message'])) {
            $data['message'] = $this->getMessage();
        }
        return response()->json($data, 200);
    }

    //-------------------------------------------- set, get ------------------------------------------------------------
    function getSuccess() {
        return $this->success;
    }

    function getMessage() {
        return $this->message;
    }

    function setSuccess($success) {
        $this->success = $success;
    }

    function setMessage($message) {
        $this->message = $message;
    }

}
