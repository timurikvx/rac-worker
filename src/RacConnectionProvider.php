<?php

namespace RacWorker;

use RacWorker\Contracts\RacConnectionProviderInterface;
use RacWorker\Entity\ClusterEntity;
use RacWorker\Entity\ConnectionEntity;
use RacWorker\Entity\InfobaseShortEntity;
use RacWorker\Entity\ProcessEntity;
use RacWorker\Factory\ClassFiller;
use RacWorker\Services\InfobaseUser;
use RacWorker\Services\RacProvider;

class RacConnectionProvider implements RacConnectionProviderInterface
{
    private RacProvider $provider;

    public function __construct(RacProvider $provider)
    {
        $this->provider = $provider;
    }

    public function list(ClusterEntity $cluster, ProcessEntity $process, InfobaseShortEntity $infobase, &$error = ''): array
    {
        $command = 'connection '.$this->provider->getHost().' list --cluster='.$cluster->uuid().' --process='.$process->uuid().' --infobase='.$infobase->uuid().$cluster->getAuth().$infobase->getAuth();
        $output = $this->provider->execute($command, $error);
        $properties = $this->connectionProperties();
        $array = $this->provider->handle($output, $properties);
        return ClassFiller::list(ConnectionEntity::class, $array, ['provider'=>$this->provider, 'cluster'=>$cluster,'process'=>$process, 'infobase'=>$infobase]);
    }

    public function getByAppID(string $appID, ClusterEntity $cluster, ProcessEntity $process, InfobaseShortEntity $infobase, &$error = ''): array
    {
        $array = [];
        $list = $this->list($cluster, $process, $infobase, $error);
        foreach ($list as $session) {
            if($session->getAppId() == $appID){
                $array[] = $session;
            }
        }
        if(count($array) == 0){
            if(empty($error)){
                $error = 'Соединения не найдены';
            }
        }
        return $array;
    }

    public function getByUser(string $user, ClusterEntity $cluster, ProcessEntity $process, InfobaseShortEntity $infobase, &$error = ''): array
    {
        $array = [];
        $list = $this->list($cluster, $process, $infobase, $error);
        foreach ($list as $session) {
            if($session->getUser() == $user){
                $array[] = $session;
            }
        }
        if(count($array) == 0){
            if(empty($error)){
                $error = 'Соединения не найдены';
            }
        }
        return $array;
    }

    public function getByHost(string $host, ClusterEntity $cluster, ProcessEntity $process, InfobaseShortEntity $infobase, &$error = ''): array
    {
        $array = [];
        $list = $this->list($cluster, $process, $infobase, $error);
        foreach ($list as $session) {
            if($session->getHost() == $host){
                $array[] = $session;
            }
        }
        if(count($array) == 0){
            if(empty($error)){
                $error = 'Соединения не найдены';
            }
        }
        return $array;
    }

    public function remove(ClusterEntity $cluster, ProcessEntity $process, ConnectionEntity $connection, InfobaseUser $infobaseUser, &$error = ''): bool
    {
        $command = 'connection '.$this->provider->getHost().' disconnect --cluster='.$cluster->uuid().' --process='.$process->uuid().' --connection='.$connection->uuid().$cluster->getAuth().$infobaseUser->getAuth();
        $this->provider->execute($command, $error);
        return empty($error);
    }

    private function connectionProperties(): array
    {
        return [
            'connection'=>fn($i)=>trim($i),
            'conn-id'=>fn($i)=>intval($i),
            'user-name'=>fn($i)=>trim($i),
            'host'=>fn($i)=>trim($i),
            'app-id'=>fn($i)=>trim($i),
            'connected-at'=>fn($i)=>trim($i),
            'thread-mode'=>fn($i)=>trim($i),
            'ib-conn-mode'=>fn($i)=>trim($i),
            'db-conn-mode'=>fn($i)=>trim($i),
            'blocked-by-dbms'=>fn($i)=>trim($i),
            'bytes-all'=>fn($i)=>intval($i),
            'bytes-last-5min'=>fn($i)=>intval($i),
            'calls-all'=>fn($i)=>intval($i),
            'calls-last-5min'=>fn($i)=>intval($i),
            'dbms-bytes-all'=>fn($i)=>intval($i),
            'dbms-bytes-last-5min'=>fn($i)=>intval($i),
            'db-proc-info'=>fn($i)=>trim($i),
            'db-proc-took'=>fn($i)=>intval($i),
            'duration-all'=>fn($i)=>intval($i),
            'duration-all-dbms'=>fn($i)=>intval($i),
            'duration-current'=>fn($i)=>intval($i),
            'duration-current-dbms'=>fn($i)=>intval($i),
            'duration-last-5min'=>fn($i)=>intval($i),
            'duration-last-5min-dbms'=>fn($i)=>intval($i),
            'memory-current'=>fn($i)=>intval($i),
            'memory-last-5min'=>fn($i)=>intval($i),
            'memory-total'=>fn($i)=>intval($i),
            'read-current'=>fn($i)=>intval($i),
            'read-last-5min'=>fn($i)=>intval($i),
            'read-total'=>fn($i)=>intval($i),
            'write-current'=>fn($i)=>intval($i),
            'write-last-5min'=>fn($i)=>intval($i),
            'write-total'=>fn($i)=>intval($i),
            'duration-current-service'=>fn($i)=>intval($i),
            'duration-last-5min-service'=>fn($i)=>intval($i),
            'duration-all-service'=>fn($i)=>intval($i),
            'current-service-name'=>fn($i)=>trim($i),
        ];
    }
    
}