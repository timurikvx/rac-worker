<?php

namespace RacWorker\Services\Cluster;

use RacWorker\Entity\ClusterEntity;

class CreateCluster
{

    private ClusterEntity $entity;

    public function __construct(ClusterEntity $entity)
    {
        $this->entity = $entity;
    }

    public function getCommand(): string
    {
        $killProblemProcesses = $this->entity->getKillProblemProcesses()? 'yes': 'no';
        $killByMemoryWithDump = $this->entity->getKillProblemProcesses()? 'yes': 'no';
        return ' --host='.$this->entity->getHost().
            ' --port='.$this->entity->getPort().
            ' --name="'.$this->entity->getName().'"'.
            ' --expiration-timeout='.$this->entity->getExpirationTimeout().
            ' --lifetime-limit='.$this->entity->getLifetimeLimit().
            ' --max-memory-size='.$this->entity->getMaxMemorySize().
            ' --max-memory-time-limit='.$this->entity->getMaxMemoryTimeLimit().
            ' --security-level='.$this->entity->getSecurityLevel().
            ' --session-fault-tolerance-level='.$this->entity->getSessionFaultToleranceLevel().
            ' --load-balancing-mode='.$this->entity->getLoadBalancingMode().
            ' --errors-count-threshold='.$this->entity->getErrorsCountThreshold().
            ' --kill-problem-processes='.$killProblemProcesses.
            ' --kill-by-memory-with-dump='.$killByMemoryWithDump;
    }
    
}