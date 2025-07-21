<?php

namespace RacWorker\Entity;

use RacWorker\Services\InfobaseUser;
use RacWorker\Traits\PropertyFill;

class InfobaseShortEntity
{
    use PropertyFill;

    protected InfobaseUser $infobaseUser;

    protected ClusterEntity $cluster;

    ///////////////////////////////////////////////////////////

    protected string $infobase;


    protected string $name;

    protected string $descr;

    public function __construct(ClusterEntity $cluster)
    {
        $this->cluster = $cluster;
        $this->infobaseUser = new InfobaseUser('', '');
    }

    protected function set(string $name, $value): void
    {
        if(is_null($value)){
            return;
        }
        $this->$name = $value;
    }

    public function setUser(InfobaseUser $infobaseUser): void
    {
        $this->infobaseUser = $infobaseUser;
    }


    public function getAuth(): string
    {
        return $this->infobaseUser->getAuth();
    }

    public function getInfobaseUser(): InfobaseUser
    {
        return $this->infobaseUser;
    }

    public function uuid(): string
    {
        return $this->infobase;
    }


    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->descr;
    }

}