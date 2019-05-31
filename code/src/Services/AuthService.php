<?php

namespace App\Services;

use App\Exceptions\NotPermittedException;
use App\Misc\Containers\Account;
use App\Misc\Containers\AdminAccount;


interface AuthService
{
    function getAccount(): Account;

    /**
     * @throws NotPermittedException
     * @return bool
     */
    function checkAdminUser():bool ;
}