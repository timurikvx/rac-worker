<?php

namespace RacWorker\Entity;

use RacWorker\Traits\PropertyFill;

class ClusterAdminEntity
{
    use PropertyFill;

    public const PWD_AUTH = 'pwd';

    public const OS_AUTH = 'os';

    public const OS_PWD_AUTH = 'pwd,os';

    protected string $name;
    protected array $auth = [];
    protected string $osUser = '';
    protected string $descr = '';
    protected string $pwd = '';

    public function __construct()
    {

    }

    protected function set(string $name, $value): void
    {
        if(is_null($value)){
            return;
        }
        if($name == 'auth'){
            $this->$name = explode('|', $value);
            return;
        }
        $this->$name = $value;
    }

    public static function create(string $name, string $description = ''): self
    {
        $admin = new self();
        $admin->setName($name);
        $admin->setDescription($description);
        return $admin;
    }

    public function setPassword(string $password): void
    {
        if(!in_array(self::PWD_AUTH, $this->auth)){
            $this->auth[] = self::PWD_AUTH;
        }
        $this->pwd = $password;
    }

    public function setOsAuth(string $user = ''): void
    {
        if(!in_array(self::OS_AUTH, $this->auth)){
            $this->auth[] = self::OS_AUTH;
        }
        $this->osUser = $user;
    }


    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }


    public function getAuth(): string
    {
        return implode(',', $this->auth);
    }

    public function getOsUser(): string
    {
        return $this->osUser;
    }

    public function getDescription(): string
    {
        return $this->descr;
    }

    public function setDescription(string $descr): void
    {
        $this->descr = $descr;
    }
    

    public function getCommand(): string
    {
        $pwd = '';
        $osUser = '';
        $auth = $this->getAuth();
        if(!empty($this->pwd)) {
            $pwd = ' --pwd='.$this->pwd;
        }
        if(!empty($this->osUser)) {
            $osUser = ' --os-user='.$this->osUser;
        }
        return '--name="'.$this->getName().'" --descr="'.$this->getDescription().'" --auth='.$auth.$pwd.$osUser;
    }

}