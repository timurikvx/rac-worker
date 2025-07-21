<?php

namespace RacWorker;

use RacWorker\Contracts\RacProcessProviderInterface;
use RacWorker\Entity\ClusterEntity;
use RacWorker\Entity\ProcessEntity;
use RacWorker\Entity\ServerEntity;
use RacWorker\Factory\ClassFiller;
use RacWorker\Services\ClusterUser;
use RacWorker\Services\RacProvider;

class RacProcessProvider implements RacProcessProviderInterface
{

    private RacProvider $provider;

    public function __construct(RacProvider $provider)
    {
        $this->provider = $provider;
    }

    public function list(ClusterEntity $cluster, ServerEntity $server, &$error = ''): array
    {
        $command = 'process '.$this->provider->getHost().' list --server='.$server->uuid().' --cluster='.$cluster->uuid().$cluster->getAuth();
        $output = $this->provider->execute($command, $error);
        $properties = $this->processProperties();
        $array = $this->provider->handle($output, $properties);
        return ClassFiller::list(ProcessEntity::class, $array);
    }

    private function processProperties(): array
    {
        return [
            'process'=>fn($i)=>trim($i),
            'host'=>fn($i)=>trim($i),
            'port'=>fn($i)=>intval($i),
            'pid'=>fn($i)=>trim($i),
            'is-enable'=>fn($i)=>($i == 'yes'),
            'running'=>fn($i)=>($i == 'yes'),
            'connections'=>fn($i)=>intval($i),
            'started-at'=>fn($i)=>new \DateTime($i),
        ];
    }

}