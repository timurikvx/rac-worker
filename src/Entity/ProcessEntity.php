<?php

namespace RacWorker\Entity;

use RacWorker\Traits\PropertyFill;

class ProcessEntity
{

    use PropertyFill;

    protected string $process;

    protected string $host;

    protected int $port;

    protected int $pid;

    protected bool $isEnable;

    protected bool $running;

    protected \DateTime $startedAt;

    protected int $connections;

    public function __construct(){

    }


    protected function set(string $name, $value): void
    {
        if(is_null($value)){
            return;
        }
        $this->$name = $value;
    }


    public function uuid(): string
    {
        return $this->process;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getPort(): int
    {
        return $this->port;
    }

    public function getPid(): int
    {
        return $this->pid;
    }

    public function isEnabled(): bool
    {
        return $this->isEnable;
    }

    public function isRunning(): bool
    {
        return $this->running;
    }

    public function getStatedAt(): \DateTime
    {
        return $this->startedAt;
    }

    public function getConnections(): int
    {
        return $this->connections;
    }

}