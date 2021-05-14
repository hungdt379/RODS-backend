<?php

namespace App\Http\Controllers;

use App\Common\Utility;
use App\Domain\Services\ServerAccountService;
use App\Http\Request\Server\ServerAccountRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BaseController;
use App\Domain\Entities\ServerAccount;
use App\Domain\Repositories\ServerAccountRepository;
use App\Domain\Entities\Server;
use App\Domain\Services\ServerService;

class ServerAccountController extends BaseController {

    /**
     * @var ServerService
     */
    private $serverAccountService;

    /**
     * ServerService constructor.
     *
     * @param ServerService $serverAccountService
     */
    public function __construct(ServerAccountService $serverAccountService) {
        $this->serverAccountService = $serverAccountService;
    }

    /**
     * 
     * @param Request $request
     * @return type
     */
    public function index(Request $request) {
        $isPaginate = !$request->get('is_paginate') || Utility::boolean($request->get('is_paginate')) == false ? false : true;
        if ($isPaginate) {
            $serverList = $this->serverAccountService->filterData(['request' => $request, 'is_paginate' => $isPaginate]);
            $perPage = (int) $request->get('take') ? (int) $request->get('take') : config('constants.RECORDS_PER_PAGE');
            return response()->json($serverList->paginate($perPage), 200);
        } else {
            $serverList = $this->serverAccountService->filterData(['request' => $request]);
            $response = [
                'status' => 200,
                'success' => true,
                'data' => $serverList,
            ];
            return $this->jsonResponse($response);
        }
    }

    public function create(ServerAccountRequest $request) {
        $serverData = $this->serverAccountService->createData($request->except('_token'));
        if ($serverData) {
            $response = [
                'status' => 200,
                'success' => true,
                'message' => __('meeting.message_create_success'),
                'createdTime' => time(),
                'errors' => [],
            ];
            return $this->jsonResponse($response);
        }
        $response = [
            'status' => 401,
            'success' => false,
            'message' => __('meeting.message_create_unsuccess'),
            'createdTime' => time(),
            'errors' => [
            ],
        ];
        return $this->jsonResponse($response);
    }

    /**
     * Store a newly created group.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request) {
        $svAccountEnti = new ServerAccount();
        $svAccountRepository = new ServerAccountRepository($svAccountEnti);
        $serverAccount = $svAccountRepository->getFirst($request->all());
        if ($serverAccount) {
            $serverData = $this->serverAccountService->updateData($serverAccount, $request->all());
            if ($serverData) {
                $response = [
                    'status' => 200,
                    'success' => true,
                    'message' => __('meeting.message_update_success'),
                    'createdTime' => time(),
                    'errors' => [],
                ];
                return $this->jsonResponse($response);
            }
        }
        $response = [
            'status' => 401,
            'success' => false,
            'message' => __('meeting.message_update_unsuccess'),
            'createdTime' => time(),
            'errors' => [],
        ];
        return $this->jsonResponse($response);
    }

    /**
     * Soft delete a group
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function delete(Request $request) {
        $id = $request->route('id');
        $data = ['id' => $id];
        $checkDelete = $this->serverAccountService->deleteData($data);
        if ($checkDelete) {
            $response = [
                'status' => 200,
                'success' => true,
                'message' => __('meeting.message_delete_success'),
                'createdTime' => time(),
                'errors' => [],
            ];
        } else {
            $response = [
                'status' => 401,
                'success' => false,
                'message' => __('meeting.message_delete_unsuccess'),
                'createdTime' => time(),
                'errors' => [],
            ];
        }
        return $this->jsonResponse($response);
    }
    
     /**
     * server account detail
     *
     * @param $id
     * @return JsonResponse
     */
    public function detail($id) {
        $serverAccount = $this->serverAccountService->getDetail($id);
        if (!$serverAccount) {
            return $this->sendError('Server Account không tồn tại', 404);
        }
        return $this->sendResponse($serverAccount, '');
    }

}
