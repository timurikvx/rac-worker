<?php

namespace RacWorker\Services;

use RacWorker\Entity\ClusterAdminEntity;

class CreateClusterAdmin
{

    private ClusterAdminEntity $admin;

    public function __construct(ClusterAdminEntity $admin)
    {
        $this->admin = $admin;
    }

    public function getCommand(): string
    {
        $pwd = '';
        $osUser = '';
        $auth = '';
        if(!empty($this->admin->getPass())) {
            $pwd = ' --pwd='.$this->admin->getPass();
            $auth = 'pwd';
        }
        if(!empty($this->admin->getOsUser())) {
            $osUser = ' --os-user='.$this->admin->getOsUser();
            $auth = 'os';
        }
        return '--name="'.$this->admin->getName().'" --descr="'.$this->admin->getDescription().'" --auth='.$auth.$pwd.$osUser;
    }

}