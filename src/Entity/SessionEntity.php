<?php

namespace RacWorker\Entity;

use RacWorker\Traits\PropertyFill;

class SessionEntity
{

    use PropertyFill;

    protected string $session;

    protected int $sessionId;

    protected string $infobase;

    protected string $connection;

    protected int $process;

    protected string $userName;

    protected string $host;

    protected string $appId;

    protected \DateTime $startedAt;

    protected  \DateTime $lastActiveAt;

    protected  bool $hibernate;

    protected int $passiveSessionHibernateTime;

    protected int $hibernateSessionTerminateTime;

    protected int $blockedByDbms;

    protected int $bytesAll;

    protected int $bytesLast5min;

    protected int $callsAll;

    protected int $callsLast5min;

    protected int $dbmsBytesAll;

    protected int $dbmsBytesLast5min;

    protected string $dbProcInfo;

    protected int $dbProcTook;

    protected int $durationAll;

    protected int $durationAllDbms;

    protected int $durationCurrent;

    protected int $durationCurrentDbms;

    protected int $durationLast5min;

    protected int $durationLast5minDbms;

    protected int $memoryCurrent;

    protected int $memoryLast5min;

    protected int $memoryTotal;

    protected int $readCurrent;

    protected int $readLast5min;

    protected int $readTotal;

    protected int $writeCurrent;

    protected int $writeLast5min;

    protected int $writeTotal;

    protected int $durationCurrentService;

    protected int $durationLast5minService;

    protected int $durationAllService;

    protected string $currentServiceName;

    protected int $cpuTimeCurrent;

    protected int $cpuTimeLast5min;

    protected int $cpuTimeTotal;

    protected string $dataSeparation;

    protected string $clientIp;

    public function __construct()
    {

    }


    protected function set(string $name, $value): void
    {
        if(is_null($value)){
            return;
        }
        if($name == 'startedAt'){
            $value = new \DateTime($value);
        }
        if($name == 'lastActiveAt'){
            $value = new \DateTime($value);
        }
        $this->$name = $value;
    }


    public function uuid(): string
    {
        return $this->session;
    }

    public function getSessionID(): int
    {
        return $this->sessionId;
    }

    public function getInfobase(): string
    {
        return $this->infobase;
    }

    public function getConnection(): string
    {
        return $this->connection;
    }

    public function getProcess(): int
    {
        return $this->process;
    }

    public function getUserName(): string
    {
        return $this->userName;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getAppId(): string
    {
        return $this->appId;
    }

    public function getStartedAt(): \DateTime
    {
        return $this->startedAt;
    }

    public function getLastActiveAt(): \DateTime
    {
        return $this->lastActiveAt;
    }

    public function isHibernate(): bool
    {
        return $this->hibernate;
    }

    public function getPassiveSessionHibernateTime(): int
    {
        return $this->passiveSessionHibernateTime;
    }

    public function getHibernateSessionTerminateTime(): int
    {
        return $this->hibernateSessionTerminateTime;
    }

    public function getBlockedByDbms(): int
    {
        return $this->blockedByDbms;
    }

    public function getBytesAll(): int
    {
        return $this->bytesAll;
    }

    public function getBytesLast5min(): int
    {
        return $this->bytesLast5min;
    }

    public function getCallsAll(): int
    {
        return $this->callsAll;
    }

    public function getCallsLast5min(): int
    {
        return $this->callsLast5min;
    }

    public function getDbmsBytesAll(): int
    {
        return $this->dbmsBytesAll;
    }

    public function getDbmsBytesLast5min(): int
    {
        return $this->dbmsBytesLast5min;
    }

    public function getDbProcInfo(): string
    {
        return $this->dbProcInfo;
    }

    public function getDbProcTook(): int
    {
        return $this->dbProcTook;
    }

    public function getDurationAll(): int
    {
        return $this->durationAll;
    }

    public function getDurationAllDbms(): int
    {
        return $this->durationAllDbms;
    }

    public function getDurationCurrent(): int
    {
        return $this->durationCurrent;
    }

    public function getDurationCurrentDbms(): int
    {
        return $this->durationCurrentDbms;
    }

    public function getDurationLast5min(): int
    {
        return $this->durationLast5min;
    }

    public function getDurationLast5minDbms(): int
    {
        return $this->durationLast5minDbms;
    }

    public function getMemCurrent(): int
    {
        return $this->memoryCurrent;
    }

    public function getMemLast5min(): int
    {
        return $this->memoryLast5min;
    }

    public function getMemTotal(): int
    {
        return $this->memoryTotal;
    }

    public function getReadCurrent(): int
    {
        return $this->readCurrent;
    }

    public function getReadLast5min(): int
    {
        return $this->readLast5min;
    }

    public function getReadTotal(): int
    {
        return $this->readTotal;
    }

    public function getWriteCurrent(): int
    {
        return $this->writeCurrent;
    }

    public function getWriteLast5min(): int
    {
        return $this->writeLast5min;
    }

    public function getWriteTotal(): int
    {
        return $this->writeTotal;
    }

    public function getDurationCurrentService(): int
    {
        return $this->durationCurrentService;
    }

    public function getDurationLast5minService(): int
    {
        return $this->durationLast5minService;
    }

    public function getDurationAllService(): int
    {
        return $this->durationAllService;
    }

    public function getCurrentServiceName(): string
    {
        return $this->currentServiceName;
    }

    public function getCpuTimeCurrent(): int
    {
        return $this->cpuTimeCurrent;
    }

    public function getCpuTimeLast5min(): int
    {
        return $this->cpuTimeLast5min;
    }

    public function getCpuTimeTotal(): int
    {
        return $this->cpuTimeTotal;
    }

    public function getDataSeparation(): string
    {
        return $this->dataSeparation;
    }

    public function getClientIp(): string
    {
        return $this->clientIp;
    }




}