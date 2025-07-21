<?php

namespace RacWorker\Entity;

class InfobaseEntity extends InfobaseShortEntity
{

    public const DB_MSSQL = 'MSSQLServer';

    public const DB_POSTGRESQL = 'PostgreSQL';

    public const DB_IBMDB2 = 'IBMDB2';

    public const DB_Oracle = 'OracleDatabase';

    protected string $dbms;

    protected string $dbServer;

    protected string $dbName;

    protected string $dbUser;

    protected bool $scheduledJobsDeny;

    protected bool $sessionsDeny;

    protected \DateTime|string $deniedFrom;

    protected string $deniedMessage;

    protected string $deniedParameter;

    protected \DateTime|string $deniedTo;

    protected string $permissionCode;

    protected bool $licenseDistribution;

    public function __construct(ClusterEntity $cluster)
    {
        parent::__construct($cluster);
    }

    protected function set(string $name, $value): void
    {
        if(is_null($value)){
            return;
        }
        if($name == 'scheduledJobsDeny'){
            $value = ($value == 'on');
        }
        if($name == 'sessionsDeny'){
            $value = ($value == 'on');
        }
        if($name == 'deniedFrom' && !empty($value)){
            $value = new \DateTime($value);
        }
        if($name == 'deniedTo' && !empty($value)){
            $value = new \DateTime($value);
        }
        $this->$name = $value;
    }

    public function setDescription(string $descr): void
    {
        $this->descr = $descr;
    }

    public function getDbName(): string
    {
        return $this->dbName;
    }

    public function setDbName(string $dbName): void
    {
        $this->dbName = $dbName;
    }

    public function getDBMS(): string
    {
        return $this->dbms;
    }

    public function setDBMS(string $dbms): void
    {
        $list = [self::DB_MSSQL, self::DB_POSTGRESQL, self::DB_IBMDB2, self::DB_Oracle];
        if(in_array($dbms, $list)){
            $this->dbms = $dbms;
        }
    }

    public function getServer(): string
    {
        return $this->dbServer;
    }

    public function setServer(string $server): void
    {
        $this->dbServer = $server;
    }

    public function getDbUsername(): string
    {
        return $this->dbUser;
    }

    public function setDbUsername(string $username): void
    {
        $this->dbUser = $username;
    }

    public function getDeniedFrom(): \DateTime|string
    {
        return $this->deniedFrom;
    }

    public function setDeniedFrom(\DateTime|string $date = ''): void
    {
        $this->deniedFrom = $date;
    }

    public function getDeniedMessage(): string
    {
        return $this->deniedMessage;
    }

    public function setDeniedMessage(string $deniedMessage): void
    {
        $this->deniedMessage = $deniedMessage;
    }

    public function getDeniedParameter(): string
    {
        return $this->deniedParameter;
    }

    public function setDeniedParameter(string $deniedParameter): void
    {
        $this->deniedParameter = $deniedParameter;
    }

    public function getDeniedTo(): \DateTime|string
    {
        return $this->deniedTo;
    }

    public function setDeniedTo(\DateTime|string $date = ''): void
    {
        $this->deniedTo = $date;
    }

    public function getPermissionCode(): string
    {
        return $this->permissionCode;
    }

    public function setPermissionCode(string $permissionCode): void
    {
        $this->permissionCode = $permissionCode;
    }

    public function getSessionsDeny(): bool
    {
        return $this->sessionsDeny;
    }

    public function setSessionsDeny(bool $sessionsDeny): void
    {
        $this->sessionsDeny = $sessionsDeny;
    }

    public function getScheduledJobsDeny(): bool
    {
        return $this->scheduledJobsDeny;
    }

    public function setScheduledJobsDeny(bool $scheduledJobsDeny): void
    {
        $this->scheduledJobsDeny = $scheduledJobsDeny;
    }

    public function getLicenseDistribution(): bool
    {
        return $this->licenseDistribution;
    }

    public function setLicenseDistribution(bool $licenseDistribution): void
    {
        $this->licenseDistribution = $licenseDistribution;
    }

}