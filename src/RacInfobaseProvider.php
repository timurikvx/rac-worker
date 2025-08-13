<?php

namespace RacWorker;

use RacWorker\Entity\ClusterEntity;
use RacWorker\Entity\InfobaseEntity;
use RacWorker\Entity\InfobaseShortEntity;
use RacWorker\Factory\ClassFiller;
use RacWorker\Services\ClusterUser;
use RacWorker\Services\Infobase\UpdateInfobase;
use RacWorker\Services\InfobaseUser;
use RacWorker\Services\RacProvider;

class RacInfobaseProvider
{
    private RacProvider $provider;

    public function __construct(RacProvider $provider)
    {
        $this->provider = $provider;
    }

    public function list(ClusterEntity $cluster, &$error = ''): array|null
    {
        $command = 'infobase '.$this->provider->getHost().' summary list --cluster='.$cluster->uuid().$cluster->getAuth();
        $output = $this->provider->execute($command, $error);
        $properties = $this->infobaseProperties();
        $array = $this->provider->handle($output, $properties);
        return ClassFiller::list(InfobaseShortEntity::class, $array, ['cluster'=>$cluster]);
    }

    public function getByName(string $name, ClusterEntity $cluster, &$error = ''): InfobaseShortEntity|null
    {
        $list = $this->list($cluster, $error);
        foreach ($list as $infobase) {
            if ($infobase->getName() == $name) {
                return $infobase;
            }
        }
        $error = 'База данных не найдена';
        return null;
    }

    public function first(ClusterEntity $cluster, &$error = ''): InfobaseShortEntity|null
    {
        $list = $this->list($cluster,$error);
        if(count($list) > 0){
            return $list[0];
        }
        $error = 'База данных не найдена';
        return null;
    }

    public function info(ClusterEntity $cluster, InfobaseShortEntity $infobase, &$error = ''): InfobaseEntity|null
    {
        $command = 'infobase '.$this->provider->getHost().' info --infobase='.$infobase->uuid().' --cluster='.$cluster->uuid().$cluster->getAuth().$infobase->getAuth();
        $output = $this->provider->execute($command, $error);
        $properties = $this->statusProperties();
        $array = $this->provider->handle($output, $properties);
        $infobase = ClassFiller::list(InfobaseEntity::class, $array, ['cluster'=>$cluster]);
        if(count($infobase) == 0){
            $error = 'База данных не найдена';
            return null;
        }
        return $infobase[0];
    }

    public function update(ClusterEntity $cluster, InfobaseEntity $infobase, &$error = ''): bool
    {
        $update = new UpdateInfobase($infobase);
        $parameters = $update->getCommand();
        $command = 'infobase '.$this->provider->getHost().' update --cluster='.$cluster->uuid().' --infobase='.$infobase->uuid().$cluster->getAuth().$infobase->getAuth().$parameters;
        $this->provider->execute($command, $error);
        return empty($error);
    }

    private function infobaseProperties(): array
    {
        return [
            'infobase'=>fn($i)=>trim($i),
            'descr'=>fn($i)=>trim(str_replace('"', '', $i)),
            'name'=>fn($i)=>trim(str_replace('"', '', $i)),
        ];
    }

    private function statusProperties(): array
    {
        return [
            'infobase'=>fn($i)=>trim($i),
            'name'=>fn($i)=>trim(str_replace('"', '', $i)),
            'dbms'=>fn($i)=>trim($i),
            'db-server'=>fn($i)=>trim($i),
            'db-name'=>fn($i)=>trim($i),
            'db-user'=>fn($i)=>trim($i),
            'security-level'=>fn($i)=>intval($i),
            'license-distribution'=>fn($i)=>trim($i),
            'scheduled-jobs-deny'=>fn($i)=>trim($i),
            'sessions-deny'=>fn($i)=>trim($i),
            'denied-from'=>fn($i)=>trim($i),
            'denied-message'=>fn($i)=>trim($i),
            'denied-parameter'=>fn($i)=>trim($i),
            'denied-to'=>fn($i)=>trim($i),
            'permission-code'=>fn($i)=>trim(str_replace('"', '', $i)),
            'descr'=>fn($i)=>trim(str_replace('"', '', $i)),
        ];
    }

}
