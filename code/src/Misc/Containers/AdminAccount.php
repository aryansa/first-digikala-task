<?php
/**
 * Created by PhpStorm.
 * User: aryanpc
 * Date: 5/21/19
 * Time: 3:11 AM
 */

namespace App\Misc\Containers;


use App\App;
use App\Roles;
use App\Services\MySqlService;

class AdminAccount implements Account
{
//    private $query;
//    private $name;
//    private $email;
//    private $id;
//    private $roles;
//
//    /**
//     * AdminAccount constructor.
//     * @param $roles
//     */
//    public function __construct(string $id)
//    {
//        $this->id = $id;
//        $this->roles = [Roles::ADMIN_ROLE];
//    }
//
//    /**
//     * @return mixed
//     */
//    public function getId()
//    {
//        return $this->id;
//    }
//
//
//    function getRoles(): array
//    {
//        // TODO: Implement getRoles() method.
//        return $this->roles;
//    }
//
//    function getName(): string
//    {
//        if (!isset($this->name))
//            $this->name = $this->getQuery()[0]['user_name'];
//        return $this->name;
//    }
//
//    function getEmail(): string
//    {
//        if (!isset($this->email))
//            $this->email = $this->getQuery()[0]['mail'];
//        return $this->email;
//    }
//
//    /**
//     * @return mixed
//     */
//    public function getQuery()
//    {
//        if (!isset($this->query)) {
//            /** @var MySqlService $database */
//            $database = App::getContainer()->get(MySqlService::class);
//            $this->query = $database->query("SELECT * FROM `Users` WHERE `id`=?", [$this->id]);
//        }
//        return $this->query;
//    }

    private $id;
    private $email;
    private $password;
    private $roles;

    /**
     * AdminAccount constructor.
     * @param $id
     * @param $email
     * @param $password
     * @param $roles
     */
    public function __construct($id, $email, $password)
    {
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
        $this->roles = [Roles::ADMIN_ROLE];
    }


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password): void
    {
        $this->password = md5($password);
    }

    /**
     * @return mixed
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @param mixed $roles
     */
    public function setRoles($roles): void
    {
        $this->roles = $roles;
    }
}