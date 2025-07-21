<?php

namespace RacWorker\Services;

class InfobaseUser
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
        return ' --infobase-user="'.$this->user.'" --infobase-pwd="'.$this->password.'"';
    }

}