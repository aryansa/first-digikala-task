<?php

namespace App\Services;

use App\Exceptions\NotPermittedException;
use App\Misc\Containers\Account;
use App\Roles;
use App\Services;
use App\Views\AdminAccountView;

class AdminAccountService implements AuthService
{

    private static $account;

    function getAccount(): Account
    {
        if (!isset(self::$account)) {
            $session = Services::sessionService();
            self::$account = (new AdminAccountView())->getAdminAccount(
                $session->getSession()->get('login', 0)
            );
        }

        return self::$account;
    }

    function checkAdminUser(): bool
    {
        $account = $this->getAccount();
        foreach ($account->getRoles() as $role) {
            if ($role == Roles::ADMIN_ROLE)
                return true;
        }

        throw new NotPermittedException();
    }
}