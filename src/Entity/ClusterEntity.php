<?php

namespace RacWorker\Entity;

use RacWorker\Services\ClusterAgent;
use RacWorker\Services\ClusterUser;
use RacWorker\Traits\PropertyFill;

class ClusterEntity
{

    use PropertyFill;

    public const BALANCE_MODE_PERFORMANCE = 'performance';
    public const BALANCE_MODE_MEMORY = 'memory';

    private ClusterUser $clusterUser;

    private ClusterAgent  $clusterAgent;

    ///////////////////////////////////////////////////////////

    protected string $cluster;

    protected string $host;

    protected int $port;

    protected string $name;

    protected int $expirationTimeout = 60;

    protected int $lifetimeLimit = 0;

    protected int $maxMemorySize = 0;

    protected int $maxMemoryTimeLimit = 0;

    protected int $securityLevel = 0;

    protected int $sessionFaultToleranceLevel = 0;

    protected string $loadBalancingMode = 'performance';

    protected int $errorsCountThreshold = 0;

    protected bool $killProblemProcesses = false;

    protected bool $killByMemoryWithDump = false;

    public function __construct()
    {
        $this->clusterUser = new ClusterUser('', '');
        $this->clusterAgent = new ClusterAgent('', '');
    }

    protected function set(string $name, $value): void
    {
        if(is_null($value)){
            return;
        }
        $this->$name = $value;
    }

    public function setUser(ClusterUser $clusterUser): void
    {
        $this->clusterUser = $clusterUser;
    }

    public function setAgent(ClusterAgent $clusterAgent): void
    {
        $this->clusterAgent = $clusterAgent;
    }


    public function getAuth(): string
    {
        return $this->clusterUser->getAuth();
    }

    public function getAgentAuth(): string
    {
        return $this->clusterAgent->getAuth();
    }

    public static function create(string $host, int $port = 1050): self
    {
        $cluster = new self();
        $cluster->setHost($host);
        $cluster->setPort($port);
        return $cluster;
    }


    public function uuid(): string
    {
        return $this->cluster;
    }


    public function getHost(): string
    {
        return $this->host;
    }

    protected function setHost(string $host): void
    {
        $this->host = $host;
    }

    public function getPort(): string
    {
        return $this->port;
    }

    protected function setPort(string $port): void
    {
        $this->port = $port;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
    

    public function getName(): string
    {
        return $this->name;
    }

    public function setExpirationTimeout(int $seconds): void
    {
        $this->expirationTimeout = $seconds;
    }

    public function getExpirationTimeout(): string
    {
        return $this->expirationTimeout;
    }

    public function setLifetimeLimit(int $seconds): void
    {
        $this->lifetimeLimit = $seconds;
    }

    public function getLifetimeLimit(): int
    {
        return $this->lifetimeLimit;
    }

    public function setMaxMemorySize(int $size): void
    {
        $this->maxMemorySize = $size;
    }

    public function getMaxMemorySize(): int
    {
        return $this->maxMemorySize;
    }

    public function setMaxMemoryTimeLimit(int $seconds): void
    {
        $this->maxMemoryTimeLimit = $seconds;
    }

    public function getMaxMemoryTimeLimit(): int
    {
        return $this->maxMemoryTimeLimit;
    }

    public function setSecurityLevel(int $level): void
    {
        $this->securityLevel = $level;
    }

    public function getSecurityLevel(): int
    {
        return $this->securityLevel;
    }

    public function setSessionFaultToleranceLevel(int $level): void
    {
        $this->sessionFaultToleranceLevel = $level;
    }

    public function getSessionFaultToleranceLevel(): int
    {
        return $this->sessionFaultToleranceLevel;
    }

    public function setLoadBalancingMode(string $mode): void
    {
        if($mode == self::BALANCE_MODE_PERFORMANCE || $mode == self::BALANCE_MODE_MEMORY) {
            $this->loadBalancingMode = $mode;
        }
    }

    public function getLoadBalancingMode(): string
    {
        return $this->loadBalancingMode;
    }

    public function setErrorsCountThreshold(int $percent): void
    {
        $this->errorsCountThreshold = $percent;
    }

    public function getErrorsCountThreshold(): int
    {
        return $this->errorsCountThreshold;
    }

    public function setKillProblemProcesses(bool $value = false): void
    {
        $this->killProblemProcesses = $value; //($value)? 'yes': 'no';
    }

    public function getKillProblemProcesses(): bool
    {
        return $this->killProblemProcesses;// == 'yes';
    }

    public function setKillByMemoryWithDump(bool $value = false): void
    {
        $this->killByMemoryWithDump = $value; //($value)? 'yes': 'no';;
    }

    public function getKillByMemoryWithDump(): bool
    {
        return $this->killByMemoryWithDump;// == 'yes';
    }

}