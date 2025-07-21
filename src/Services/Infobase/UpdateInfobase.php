<?php

namespace RacWorker\Services\Infobase;

use RacWorker\Entity\InfobaseEntity;

class UpdateInfobase
{

    private InfobaseEntity $entity;

    public function __construct(InfobaseEntity $infobase)
    {
        $this->entity = $infobase;
    }

    public function getCommand(): string
    {
        $sessionsDeny = $this->entity->getSessionsDeny()? 'on': 'off';
        $scheduledJobsDeny = $this->entity->getScheduledJobsDeny()? 'on': 'off';
        $licenseDistribution = $this->entity->getLicenseDistribution()? 'allow': 'deny';
        $deniedFrom = $this->entity->getDeniedFrom();
        $deniedFromString = ($deniedFrom instanceof \DateTime)? $deniedFrom->format('Y-m-d H:i:s'): '';
        $deniedTo = $this->entity->getDeniedTo();
        $deniedToString = ($deniedTo instanceof \DateTime)? $deniedTo->format('Y-m-d H:i:s'): '';
        return ' --descr="'.$this->entity->getDescription().'"'.
            ' --dbms='.$this->entity->getDBMS().
            ' --db-server="'.$this->entity->getServer().'"'.
            ' --db-name="'.$this->entity->getDbName().'"'.
            //' --db-user='.$this->entity->getUsername().
            //' --db-pwd=""'.
            ' --denied-from='.$deniedFromString.
            ' --denied-message="'.$this->entity->getDeniedMessage().'"'.
            ' --denied-parameter="'.$this->entity->getDeniedParameter().'"'.
            ' --denied-to='.$deniedToString.
            ' --permission-code='.$this->entity->getPermissionCode().
            ' --sessions-deny='.$sessionsDeny.
            ' --scheduled-jobs-deny="'.$scheduledJobsDeny.'"'.
            ' --license-distribution='.$licenseDistribution;
    }

}