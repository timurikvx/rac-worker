<?php

namespace RacWorker;

use RacWorker\Entity\AgentEntity;
use RacWorker\Entity\ClusterAdminEntity;
use RacWorker\Factory\ClassFiller;
use RacWorker\Services\ClusterAgent;
use RacWorker\Services\RacProvider;

class RacAgentProvider
{

    private RacProvider $provider;

    public function __construct(RacProvider $provider)
    {
        $this->provider = $provider;
    }

    public function add(AgentEntity $agentEntity, ClusterAgent $clusterAgent, &$error = ''): bool
    {
        $parameters = $agentEntity->getCommand();
        $command = 'agent '.$this->provider->getHost().' admin register'.$clusterAgent->getAuth().' '.$parameters;
        $this->provider->execute($command, $error);
        return empty($error);
    }

    public function list(ClusterAgent $clusterAgent, &$error = ''): array
    {
        $command = 'agent '.$this->provider->getHost().' admin list'.$clusterAgent->getAuth();
        $output = $this->provider->execute($command, $error);
        $properties = $this->agentProperties();
        $array = $this->provider->handle($output, $properties);
        return ClassFiller::list(AgentEntity::class, $array);
    }

    public function remove(AgentEntity $agentEntity, ClusterAgent $clusterAgent, &$error = ''): bool
    {
        $command = 'agent '.$this->provider->getHost().' admin remove --name='.$agentEntity->getName().$clusterAgent->getAuth();
        $this->provider->execute($command, $error);
        return empty($error);
    }
    public function version(): string
    {
        return '';
    }

    private function agentProperties(): array
    {
        return [
            'name'=>fn($i)=>trim($i),
            'auth'=>fn($i)=>trim($i),
            'os-user'=>fn($i)=>trim($i),
            'descr'=>fn($i)=>trim(str_replace('"', '', $i)),
        ];
    }

}