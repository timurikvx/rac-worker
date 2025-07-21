<?php

namespace RacWorker;

use RacWorker\Entity\ClusterEntity;
use RacWorker\Entity\ServerEntity;
use RacWorker\Factory\ClassFiller;
use RacWorker\Services\ClusterUser;
use RacWorker\Services\RacProvider;
use RacWorker\Services\Server\CreateServer;
use RacWorker\Services\Server\UpdateServer;

class RacServerProvider
{

    private RacProvider $provider;

    public function __construct(RacProvider $provider)
    {
        $this->provider = $provider;
    }

    public function list(ClusterEntity $cluster, &$error = ''): array
    {
        $command = 'server '.$this->provider->getHost().' list --cluster='.$cluster->uuid().$cluster->getAuth();
        $output = $this->provider->execute($command, $error);
        $properties = $this->serverProperties();
        $array = $this->provider->handle($output, $properties);
        return ClassFiller::list(ServerEntity::class, $array);
    }

    public function first(ClusterEntity $cluster, &$error = ''): ServerEntity|null
    {
        $servers = $this->list($cluster, $error);
        if(count($servers) > 0){
            return $servers[0];
        }
        return null;
    }

    public function getByName(string $name, ClusterEntity $cluster, &$error = ''): ServerEntity|null
    {
        $servers = $this->list($cluster, $error);
        foreach($servers as $server){
            if($server->getName() == $name){
                return $server;
            }
        }
        return null;
    }

    public function info(ClusterEntity $cluster, ServerEntity $server, &$error = ''): ServerEntity| null
    {
        $command = 'server '.$this->provider->getHost().' info --cluster='.$cluster->uuid().' --server='.$server->uuid().$cluster->getAuth();
        $output = $this->provider->execute($command, $error);
        $properties = $this->serverProperties();
        $array = $this->provider->handle($output, $properties);
        if(count($array) === 0){
            return null;
        }
        return ClassFiller::item(ServerEntity::class, $array[0]);
    }

    public function add(ClusterEntity $cluster, ServerEntity $server, &$error = ''): string|null
    {
        $createServer = new CreateServer($server);
        $parameters = $createServer->getCommand();
        $command = 'server '.$this->provider->getHost().' insert --cluster='.$cluster->uuid().$cluster->getAuth().$parameters;
        $output = $this->provider->execute($command, $error);
        $properties = $this->createServerProperties();
        $array = $this->provider->handle($output, $properties);
        if(count($array) === 0){
            return null;
        }
        return $array[0]['server'];
    }

    public function remove(ClusterEntity $cluster, ServerEntity $server, &$error = ''): bool
    {
        $command = 'server '.$this->provider->getHost().' remove --cluster='.$cluster->uuid().$cluster->getAuth().' --server='.$server->uuid();
        $this->provider->execute($command, $error);
        return empty($error);
    }

    public function update(ClusterEntity $cluster, ServerEntity $server, &$error = ''): bool
    {
        $updateServer = new UpdateServer($server);
        $parameters = $updateServer->getCommand();
        $command = 'server '.$this->provider->getHost().' update --cluster='.$cluster->uuid().$cluster->getAuth().$parameters;
        $this->provider->execute($command, $error);
        return empty($error);
    }

    private function serverProperties(): array
    {
        return [
            'server'=>fn($i)=>trim($i),
            'agent-host'=>fn($i)=>trim($i),
            'agent-port'=>fn($i)=>trim($i),
            'port-range'=>fn($i)=>trim($i),
            'name'=>fn($i)=>trim(str_replace('"', '', $i)),
            'using'=>fn($i)=>trim($i),
            'dedicate-managers'=>fn($i)=>trim($i),
            'infobases-limit'=>fn($i)=>intval($i),
            'memory-limit'=>fn($i)=>intval($i),
            'connections-limit'=>fn($i)=>intval($i),
            'safe-working-processes-memory-limit'=>fn($i)=>intval($i),
            'safe-call-memory-limit'=>fn($i)=>intval($i),
            'cluster-port'=>fn($i)=>intval($i),
            'critical-total-memory'=>fn($i)=>intval($i),
            'temporary-allowed-total-memory'=>fn($i)=>intval($i),
            'temporary-allowed-total-memory-time-limit'=>fn($i)=>intval($i),
            'service-principal-name'=>fn($i)=>trim($i),
            'speech-to-text-model-directory'=>fn($i)=>trim($i),
        ];
    }

    private function createServerProperties(): array
    {
        return [
            'server'=>fn($i)=>trim($i),
        ];
    }

}