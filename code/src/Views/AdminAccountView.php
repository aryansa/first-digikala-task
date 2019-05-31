<?php

namespace App\Views;


use App\Misc\Containers\Account;
use App\Misc\Containers\AdminAccount;
use App\Misc\Containers\GuestAccount;
use App\Services;

class AdminAccountView
{
    public function getAdminAccount(int $id): Account
    {
        if ($id == 0) {
            return new GuestAccount();
        }

        $userData = Services::mysqlService()->select('users', [$id]);

        if (empty($userData)) {
            return new GuestAccount();
        }

        return new AdminAccount($userData[$id]['id'], $userData[$id]['mail'], $userData[$id]['password']);
    }
}