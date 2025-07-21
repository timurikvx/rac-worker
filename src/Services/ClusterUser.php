<?php

namespace RacWorker\Services;

class ClusterUser
{

    protected string $user = '';

    protected string $password = '';

    public function __construct(string $user = '', string $password = '')
    {
        $this->user = $user;
        $this->password = $password;
    }

    public function getAuth(): string
    {
        return ' --cluster-user="'.$this->user.'" --cluster-pwd="'.$this->password.'"';
    }

}