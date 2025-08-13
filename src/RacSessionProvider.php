<?php

namespace RacWorker;

use RacWorker\Entity\ClusterEntity;
use RacWorker\Entity\InfobaseShortEntity;
use RacWorker\Entity\SessionEntity;
use RacWorker\Factory\ClassFiller;
use RacWorker\Services\RacProvider;

class RacSessionProvider
{

    private RacProvider $provider;

    public function __construct(RacProvider $provider)
    {
        $this->provider = $provider;
    }

    public function list(ClusterEntity $cluster, InfobaseShortEntity $infobase, &$error = ''): array
    {
        $command = 'session '.$this->provider->getHost().' list --cluster='.$cluster->uuid().' --infobase='.$infobase->uuid().$cluster->getAuth();
        $output = $this->provider->execute($command, $error);
        $properties = $this->sessionProperties();
        $array = $this->provider->handle($output, $properties);
        return ClassFiller::list(SessionEntity::class, $array, ['provider'=>$this->provider, 'cluster'=>$cluster]);
    }

    public function getByAppID(string $appID, ClusterEntity $cluster, InfobaseShortEntity $infobase, &$error = ''): array
    {
        $array = [];
        $list = $this->list($cluster, $infobase, $error);
        foreach ($list as $session) {
            if($session->getAppId() == $appID){
                $array[] = $session;
            }
        }
        if(count($array) == 0){
            if(empty($error)){
                $error = 'Сессии не найдены';
            }
        }
        return $array;
    }

    public function getByUser(string $user, ClusterEntity $cluster, InfobaseShortEntity $infobase, &$error = ''): array
    {
        $array = [];
        $list = $this->list($cluster, $infobase, $error);
        foreach ($list as $session) {
            if($session->getUserName() == $user){
                $array[] = $session;
            }
        }
        if(count($array) == 0){
            if(empty($error)){
                $error = 'Сессии не найдены';
            }
        }
        return $array;
    }

    public function getByHost(string $host, ClusterEntity $cluster, InfobaseShortEntity $infobase, &$error = ''): array
    {
        $array = [];
        $list = $this->list($cluster, $infobase, $error);
        foreach ($list as $session) {
            if($session->getHost() == $host){
                $array[] = $session;
            }
        }
        if(count($array) == 0){
            $error = 'Сессии не найдены';
        }
        return $array;
    }

    public function remove(ClusterEntity $cluster, SessionEntity $session, string $message = '', &$error = ''): bool
    {
        $command = 'session '.$this->provider->getHost().' terminate --cluster='.$cluster->uuid().$cluster->getAuth().' --session='.$session->uuid().' --error-message="'.$message.'"';
        $this->provider->execute($command, $error);
        return empty($error);
    }

    public function removeServerCall(ClusterEntity $cluster, SessionEntity $session, string $message = '', &$error = ''): bool
    {
        $command = 'session '.$this->provider->getHost().' interrupt-current-server-call --cluster='.$cluster->uuid().$cluster->getAuth().' --session='.$session->uuid().' --error-message="'.$message.'"';
        $this->provider->execute($command, $error);
        return empty($error);
    }

    private function sessionProperties(): array
    {
        return [
            'session'=>fn($i)=>trim($i),
            'session-id'=>fn($i)=>trim($i),
            'infobase'=>fn($i)=>trim($i),
            'connection'=>fn($i)=>trim($i),
            'process'=>fn($i)=>trim($i),
            'user-name'=>fn($i)=>trim($i),
            'host'=>fn($i)=>trim($i),
            'app-id'=>fn($i)=>trim($i),
            'started-at'=>fn($i)=>trim($i),
            'last-active-at'=>fn($i)=>trim($i),
            'hibernate'=>fn($i)=>trim($i),
            'passive-session-hibernate-time'=>fn($i)=>intval($i),
            'hibernate-session-terminate-time'=>fn($i)=>intval($i),
            'blocked-by-dbms'=>fn($i)=>intval($i),
            'blocked-by-ls '=>fn($i)=>intval($i),
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
            'cpu-time-current'=>fn($i)=>intval($i),
            'cpu-time-last-5min'=>fn($i)=>intval($i),
            'cpu-time-total'=>fn($i)=>intval($i),
            'data-separation'=>fn($i)=>trim(str_replace('\'', '', $i)),
            'client-ip'=>fn($i)=>trim($i),
        ];
    }

}