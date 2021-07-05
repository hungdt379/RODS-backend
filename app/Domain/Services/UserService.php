<?php


namespace App\Domain\Services;


use App\Domain\Entities\User;
use App\Domain\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use PDF;

class UserService
{
    private $userRepository;


    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
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
        $user['remember_token'] = [];
        $user->is_active = false;
        $user->number_of_customer = 0;
        return $this->userRepository->update($user);
    }

    public function updateNumberOfCustomer($userID, $numberOfCustomer)
    {
        $updateUser = $this->getUserById($userID);
        $updateUser->number_of_customer = (int)$numberOfCustomer;

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

    public function deleteTable($tableId)
    {
        $this->userRepository->deleteTable($tableId);
    }

    public function generateNewQrCode($tableId)
    {
        $table = $this->getUserById($tableId);
        $table->password = Hash::make(time());
        $this->userRepository->update($table);
        QrCode::size(500)->format('svg')
            ->generate('http://rdos.funitekit.com/customer-login?username='.$table->username.'&password='.time(), 'qrcode/'.time().'.svg');
        $customPaper = array(0,0,380.10,283.80); // (10*20 cm)
        $pdf = PDF::loadHTML('<h1>'.$table->full_name.'</h1>'.
                            '<img style="width:290px, height:290px" src="qrcode/'.time().'.svg" alt="">')
                            ->setPaper($customPaper, 'landscape');
        $nameFile = '_' . time() . '.pdf';
        Storage::disk('public')->put($nameFile, $pdf->output());
        return $url = asset('export/' . $nameFile);
    }
}
