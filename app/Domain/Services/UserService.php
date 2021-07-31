<?php


namespace App\Domain\Services;


use App\Domain\Entities\User;
use App\Domain\Repositories\OrderRepository;
use App\Domain\Repositories\QueueOrderRepository;
use App\Domain\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use PDF;
use JWTAuth;

class UserService
{
    private $userRepository;
    private $cartService;
    private $orderRepository;
    private $queueOrderRepository;


    /**
     * UserService constructor.
     * @param UserRepository $userRepository
     * @param CartService $cartService
     * @param OrderRepository $orderRepository
     * @param QueueOrderRepository $queueOrderRepository
     */
    public function __construct(UserRepository $userRepository, CartService $cartService, OrderRepository $orderRepository, QueueOrderRepository $queueOrderRepository)
    {
        $this->userRepository = $userRepository;
        $this->cartService = $cartService;
        $this->orderRepository = $orderRepository;
        $this->queueOrderRepository = $queueOrderRepository;
    }

    public function appendRememberToken($token, $userID)
    {
        $this->userRepository->appendRememberToken($token, $userID);
    }

    public function getListTable($pageSize)
    {
        return $this->userRepository->getListTable($pageSize);
    }

    public function getUserById($userID)
    {
        return $this->userRepository->getUserById($userID);
    }

    public function openTable($userID, $numberOfCustomer)
    {
        $updateUser = $this->getUserById($userID);
        $updateUser->is_active = true;
        $updateUser->number_of_customer = (int)$numberOfCustomer;
        $this->userRepository->update($updateUser);

        return $updateUser;
    }

    public function closeTable($user)
    {
        if (sizeof($user->remember_token) != 0) {
            $ctoken = JWTAuth::getToken();
            foreach ($user['remember_token'] as $token) {
                if ($token != $ctoken) {
                    JWTAuth::setToken($token);
                    JWTAuth::invalidate();
                }
            }
            JWTAuth::setToken($ctoken);
        }

        $this->cartService->delete($user->_id);

        $user['remember_token'] = [];
        $user->is_active = false;
        $user->number_of_customer = 0;
        return $this->userRepository->update($user);
    }

    private function checkComboExistInOrder($haystack)
    {
        foreach ($haystack['item'] as $value) {
            if (strpos($value['detail_item']['name'], 'Combo') !== false) {
                return true;
            }
        }
        return false;
    }

    private function updateNumberOfCustomerOrder($haystack, $numberOfCustomer, $repository)
    {
        $item = [];
        $tempTotalCost = 0;
        foreach ($haystack['item'] as $value) {
            if (strpos($value['detail_item']['name'], 'Combo') !== false) {
                $haystack['total_cost'] -= ($value['quantity'] * $value['detail_item']['cost']);
                $value['quantity'] = $numberOfCustomer;
                $value['total_cost'] = $value['quantity'] * $value['detail_item']['cost'];
                $tempTotalCost = $numberOfCustomer * $value['detail_item']['cost'];
            }
            array_push($item, $value);
        }
        $haystack['item'] = $item;
        $haystack['total_cost'] += $tempTotalCost;
        $haystack['number_of_customer'] = $numberOfCustomer;
        $repository->update($haystack);
    }

    public function updateNumberOfCustomer($tableId, $numberOfCustomer)
    {
        $confirmOrder = $this->orderRepository->getConfirmOrder($tableId);
        $queueOrder = $this->queueOrderRepository->getQueueOrderByTableID($tableId);
        if (!$confirmOrder && $queueOrder) {
            if ($this->checkComboExistInOrder($queueOrder)) {
                $this->updateNumberOfCustomerOrder($queueOrder, $numberOfCustomer, $this->queueOrderRepository);
            }
        } elseif ($confirmOrder) {
            if ($this->checkComboExistInOrder($confirmOrder)) {
                if ($queueOrder) {
                    if ($this->checkComboExistInOrder($queueOrder)) {
                        $item = [];
                        foreach ($queueOrder['item'] as $value) {
                            if (strpos($value['detail_item']['name'], 'Combo') !== false)
                                $value['quantity'] = $numberOfCustomer;

                            array_push($item, $value);
                        }
                        $queueOrder['item'] = $item;
                        $queueOrder['number_of_customer'] = $numberOfCustomer;
                        $this->queueOrderRepository->update($queueOrder);
                    }
                }
                $this->updateNumberOfCustomerOrder($confirmOrder, $numberOfCustomer, $this->orderRepository);
            } else {
                if ($queueOrder)
                    if ($this->checkComboExistInOrder($queueOrder))
                        $this->updateNumberOfCustomerOrder($queueOrder, $numberOfCustomer, $this->queueOrderRepository);
            }
        }

        $updateUser = $this->getUserById($tableId);
        $updateUser->number_of_customer = $numberOfCustomer;
        return $this->userRepository->update($updateUser);

    }

    public function addNewTable($username, $fullname, $maxCustomer)
    {
        $user = new User();
        $user->full_name = $fullname;
        $user->username = $username;
        $user->password = Hash::make(123);
        $user->is_active = false;
        $user->role = 't';
        $user->max_customer = $maxCustomer;
        $user->number_of_customer = 0;
        $user->remember_token = [];
        $this->userRepository->update($user);
    }

    public function checkExistedTable($username)
    {
        $table = $this->userRepository->checkExistedTable($username);
        if ($table == null) {
            return true;
        } else return false;
    }

    public function checkExistedTableForUpdate($username, $currentUsername)
    {
        $table = $this->userRepository->checkExistedTableForUpdate($username, $currentUsername);
        if ($table == null) {
            return true;
        } else return false;
    }

    public function updateTable($tableId, $username, $fullname, $maxCustomer)
    {
        $table = $this->userRepository->getUserById($tableId);
        $table->full_name = $fullname;
        $table->username = $username;
        $table->max_customer = $maxCustomer;
        $this->userRepository->update($table);
    }

    public function deleteTable($tableId)
    {
        $this->userRepository->deleteTable($tableId);
    }

    public function generateNewQrCode($table)
    {

        $table->password = Hash::make(time());
        $this->userRepository->update($table);
        $qrCode = QrCode::size(500)->format('svg')
            ->generate('http://rdos.funitekit.com/customer-login?username=' . $table->username . '&password=' . time());
        $nameQrFile = time() . '.svg';
        Storage::disk('qrcode')->put($nameQrFile, $qrCode);

        $customPaper = array(0, 0, 380.10, 283.80);
        $pdf = PDF::loadHTML('<h1>' . $table->full_name . '</h1>' .
            '<img style="width:290px, height:290px" src="qrcode/' . time() . '.svg" alt="">')
            ->setPaper($customPaper, 'landscape');
        $nameFile = '_' . time() . '.pdf';
        Storage::disk('export')->put($nameFile, $pdf->output());
        return $url = asset('export/' . $nameFile);
    }

    public function getTableNotActive()
    {
        return $this->userRepository->getTableNotActive();
    }
}
