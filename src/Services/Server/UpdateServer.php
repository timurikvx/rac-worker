<?php

namespace RacWorker\Services\Server;

use RacWorker\Entity\ServerEntity;

class UpdateServer
{
    private ServerEntity $entity;

    public function __construct(ServerEntity $server)
    {
        $this->entity = $server;
    }

    public function getCommand(bool $update = false): string
    {
        return ' --server='.$this->entity->uuid().
            ' --port-range='.$this->entity->getPortMin().':'.$this->entity->getPortMax().
            ' --using='.$this->entity->getUsing().
            ' --infobases-limit='.$this->entity->getInfobasesLimit().
            ' --memory-limit='.$this->entity->getMemoryLimit().
            ' --connections-limit='.$this->entity->getConnectionsLimit().
            ' --dedicate-managers='.$this->entity->getDedicateManagers().
            ' --safe-working-processes-memory-limit='.$this->entity->getSafeWorkingProcessesMemoryLimit().
            ' --safe-call-memory-limit='.$this->entity->getSafeCallMemoryLimit().
            ' --critical-total-memory='.$this->entity->getCriticalTotalMemory().
            ' --temporary-allowed-total-memory='.$this->entity->getTemporaryAllowedTotalMemory().
            ' --temporary-allowed-total-memory-time-limit='.$this->entity->getTemporaryAllowedTotalMemoryTimeLimit().
            ' --service-principal-name="'.'"';
    }

}