<?php

namespace RacWorker;

use RacWorker\Contracts\RacClusterProviderInterface;
use RacWorker\Entity\ClusterAdminEntity;
use RacWorker\Entity\ClusterEntity;
use RacWorker\Factory\ClassFiller;
use RacWorker\Services\Cluster\CreateCluster;
use RacWorker\Services\Cluster\UpdateCluster;
use RacWorker\Services\ClusterAgent;
use RacWorker\Services\ClusterUser;
use RacWorker\Services\RacProvider;

class RacClusterProvider implements RacClusterProviderInterface
{

    private RacProvider $provider;

    public function __construct(RacProvider $provider)
    {
        $this->provider = $provider;
    }

    public function adminList(ClusterEntity $cluster, &$error = ''): array
    {
        $command = 'cluster '.$this->provider->getHost().' admin list --cluster='.$cluster->uuid().$cluster->getAuth();
        $output = $this->provider->execute($command, $error);
        $properties = $this->adminProperties();
        $array = $this->provider->handle($output, $properties);
        return ClassFiller::list(ClusterAdminEntity::class, $array);
    }

    public function adminAdd(ClusterEntity $cluster, ClusterAdminEntity $admin, &$error = ''): bool
    {
        $parameters = $admin->getCommand();
        $command = 'cluster '.$this->provider->getHost().' admin register --cluster='.$cluster->uuid().$cluster->getAuth().' '.$parameters;
        $this->provider->execute($command, $error);
        return empty($error);
    }

    public function adminRemove(ClusterEntity $cluster, ClusterAdminEntity $admin, &$error = ''): bool
    {
        $command = 'cluster '.$this->provider->getHost().' admin remove --name="'.$admin->getName().'" --cluster='.$cluster->uuid().$cluster->getAuth();
        $this->provider->execute($command, $error);
        return empty($error);
    }

    public function getByName(string $name, &$error = ''): ClusterEntity|null
    {
        $clusters = $this->list($error);
        foreach($clusters as $cluster){
            if($cluster->getName() == $name){
                return $cluster;
            }
        }
        return null;
    }

    public function info(ClusterEntity $cluster, &$error = ''): ClusterEntity|null
    {
        $command = 'cluster '.$this->provider->getHost().' info --cluster='.$cluster->uuid();
        $output = $this->provider->execute($command, $error);
        $properties = $this->clusterProperties();
        $array = $this->provider->handle($output, $properties);
        if(count($array) === 0){
            return null;
        }
        return ClassFiller::item(ClusterEntity::class, $array[0]);
    }

    public function first(&$error = ''): ClusterEntity|null
    {
        $clusters = $this->list($error);
        if(count($clusters) === 0) {
            return null;
        }
        return $clusters[0];
    }

    public function list(&$error = ''): array
    {
        $command = 'cluster '.$this->provider->getHost().' list';
        $output = $this->provider->execute($command, $error);
        $properties = $this->clusterProperties();
        $array = $this->provider->handle($output, $properties);
        return ClassFiller::list(ClusterEntity::class, $array);
    }

    public function add(ClusterEntity $cluster, &$error = ''): string|null
    {
        $createCluster = new CreateCluster($cluster);
        $parameters = $createCluster->getCommand();
        $command = 'cluster '.$this->provider->getHost().' insert '.$parameters.$cluster->getAgentAuth();
        $output = $this->provider->execute($command, $error);
        if(!empty($error)){
            return null;
        }
        $properties = $this->createClusterProperties();
        $array = $this->provider->handle($output, $properties);
        return $array[0]['cluster'];
    }

    public function update(ClusterEntity $cluster, &$error = ''): bool
    {
        $createCluster = new UpdateCluster($cluster);
        $parameters = $createCluster->getCommand();
        $command = 'cluster '.$this->provider->getHost().' update '.$parameters.$cluster->getAgentAuth();
        $this->provider->execute($command, $error);
        return empty($error);
    }

    public function remove(ClusterEntity $cluster, &$error = ''): bool
    {
        $command = 'cluster '.$this->provider->getHost().' remove --cluster='.$cluster->uuid().$cluster->getAgentAuth();
        $this->provider->execute($command, $error);
        return empty($error);
    }

    private function clusterProperties(): array
    {
        return [
            'cluster'=>fn($i)=>trim($i),
            'host'=>fn($i)=>trim($i),
            'port'=>fn($i)=>intval($i),
            'name'=>fn($i)=>trim(str_replace('"', '', $i)),
            'expiration-timeout'=>fn($i)=>intval($i),
            'lifetime-limit'=>fn($i)=>intval($i),
            'max-memory-size'=>fn($i)=>intval($i),
            'max-memory-time-limit'=>fn($i)=>intval($i),
            'security-level'=>fn($i)=>intval($i),
            'session-fault-tolerance-level'=>fn($i)=>intval($i),
            'load-balancing-mode'=>fn($i)=>trim($i),
            'errors-count-threshold'=>fn($i)=>intval($i),
            'kill-problem-processes'=>fn($i)=>boolval($i),
            'kill-by-memory-with-dump'=>fn($i)=>boolval($i),
        ];
    }

    private function adminProperties(): array
    {
        return [
            'name'=>fn($i)=>trim($i),
            'auth'=>fn($i)=>trim($i),
            'os-user'=>fn($i)=>trim($i),
            'descr'=>fn($i)=>trim(str_replace('"', '', $i)),
        ];
    }

    private function createClusterProperties(): array
    {
        return [
            'cluster'=>fn($i)=>trim($i),
        ];
    }

}