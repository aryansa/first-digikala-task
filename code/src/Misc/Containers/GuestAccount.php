<?php
/**
 * Created by PhpStorm.
 * User: aryanpc
 * Date: 5/21/19
 * Time: 3:20 AM
 */

namespace App\Misc\Containers;


use App\Roles;

class GuestAccount extends AdminAccount
{

    private $name;
    private $email;
    private $roles ;

    /**
     * GuestAccount constructor.
     * @param $roles
     */
    public function __construct()
    {
        $this->roles = [Roles::GUST_ROLE];
        $this->email = "gust@gust.com";
        $this->name = "Gust";
    }


    function getRoles(): array
    {
        // TODO: Implement getRoles() method.
        return $this->roles;
    }

    function getName(): string
    {
        // TODO: Implement getName() method.
        return $this->name ;
    }

    function getEmail(): string
    {
        // TODO: Implement getEmail() method.
        return $this->email ;
    }

}