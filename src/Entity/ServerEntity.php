<?php

namespace RacWorker\Entity;

use RacWorker\Traits\PropertyFill;

class ServerEntity
{

    use PropertyFill;

    public const USING_MAIN = 'main';

    public const USING_NORMAL = 'normal';

    public const DEDICATE_MANAGERS_ALL = 'all';

    public const DEDICATE_MANAGERS_NONE = 'none';

    protected string $server;

    protected string $agentHost;

    protected int $agentPort;

    protected int $portMin = 1560;

    protected int $portMax = 1591;

    protected string $name;

    protected string $using = 'normal';

    protected int $infobasesLimit = 0;

    protected int $memoryLimit = 0;

    protected int $connectionsLimit = 0;

    protected int $clusterPort = 0;

    protected string $dedicateManagers = 'none';

    protected int $safeWorkingProcessesMemoryLimit = 0;

    protected int $safeCallMemoryLimit = 0;

    protected int $criticalTotalMemory = 0;

    protected int $temporaryAllowedTotalMemory = 0;

    protected int $temporaryAllowedTotalMemoryTimeLimit = 0;

    protected string $servicePrincipalName = '';

    public function __construct()
    {

    }

    protected function set(string $name, $value): void
    {
        if(is_null($value)){
            return;
        }
        $this->$name = $value;
    }

    public static function create(string $name, string $agentHost, int $agentPort): self
    {
        $server = new self();
        $server->name = $name;
        $server->agentHost = $agentHost;
        $server->agentPort = $agentPort;
        return $server;
    }

    public function uuid(): string
    {
        return $this->server;
    }

    public function getHost(): string
    {
        return $this->agentHost;
    }

    public function getPort(): int
    {
        return $this->agentPort;
    }

    /**
     * @throws \Exception
     */
    public function setPortRange(string $portRange): void
    {
        $ports = explode(':', $portRange);
        if(count($ports) < 2){
            throw new \Exception('Неверное значение диапазона портов, требуется разделение ":"');
        }
        $this->portMin = intval($ports[0]);
        $this->portMax = intval($ports[1]);
    }

    public function getPortMin(): int
    {
        return $this->portMin;
    }

    public function getPortMax(): int
    {
        return $this->portMax;
    }


    public function getName(): string
    {
        return $this->name;
    }

    public function setUsing(string $using): void
    {
        if($this::USING_MAIN == $using || $this::USING_NORMAL == $using){
            $this->using = $using;
        }
    }

    public function getUsing(): string
    {
        return $this->using;
    }

    public function setInfobasesLimit(int $limit): void
    {
        $this->infobasesLimit = $limit;
    }

    public function getInfobasesLimit(): int
    {
        return $this->infobasesLimit;
    }

//    public function setMemoryLimit(int $limit): void
//    {
//        $this->memoryLimit = $limit;
//    }

    public function getMemoryLimit(): int
    {
        return $this->memoryLimit;
    }

    public function setConnectionsLimit(int $limit): void
    {
        $this->connectionsLimit = $limit;
    }

    public function getConnectionsLimit(): int
    {
        return $this->connectionsLimit;
    }

    public function getClusterPort(): int
    {
        return $this->clusterPort;
    }

    public function setDedicateManagers(string $dedicateManagers): void
    {
        if($this::DEDICATE_MANAGERS_NONE == $dedicateManagers || $this::DEDICATE_MANAGERS_ALL == $dedicateManagers){
            $this->dedicateManagers = $dedicateManagers;
        }
    }

    public function getDedicateManagers(): string
    {
        return $this->dedicateManagers;
    }

//    public function setSafeWorkingProcessesMemoryLimit(int $memoryLimit): void
//    {
//        $this->safeWorkingProcessesMemoryLimit = $memoryLimit;
//    }

    public function getSafeWorkingProcessesMemoryLimit(): int
    {
        return $this->safeWorkingProcessesMemoryLimit;
    }

    public function setSafeCallMemoryLimit(int $memoryLimit): void
    {
        $this->safeCallMemoryLimit = $memoryLimit;
    }

    public function getSafeCallMemoryLimit(): int
    {
        return $this->safeCallMemoryLimit;
    }

    public function setCriticalTotalMemory(int $criticalTotalMemory): void
    {
        $this->criticalTotalMemory = $criticalTotalMemory;
    }

    public function getCriticalTotalMemory(): int
    {
        return $this->criticalTotalMemory;
    }

    public function setTemporaryAllowedTotalMemory(int $temporaryAllowedTotalMemory): void
    {
        $this->temporaryAllowedTotalMemory = $temporaryAllowedTotalMemory;
    }

    public function getTemporaryAllowedTotalMemory(): int
    {
        return $this->temporaryAllowedTotalMemory;
    }

    public function setTemporaryAllowedTotalMemoryTimeLimit(int $temporaryAllowedTotalMemoryTimeLimit): void
    {
        $this->temporaryAllowedTotalMemoryTimeLimit = $temporaryAllowedTotalMemoryTimeLimit;
    }

    public function getTemporaryAllowedTotalMemoryTimeLimit(): int
    {
        return $this->temporaryAllowedTotalMemoryTimeLimit;
    }

}