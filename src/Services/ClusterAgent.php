<?php

namespace RacWorker\Services;

class ClusterAgent
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
        return ' --agent-user="'.$this->user.'" --agent-pwd="'.$this->password.'"';
    }

}