<?php

namespace RacWorker;

use RacWorker\Services\RacProvider;

class RacWorker
{

    private RacProvider $provider;

    public RacClusterProvider $cluster;

    public RacConnectionProvider $connection;

    public RacProcessProvider $process;

    public RacServerProvider $server;

    public RacInfobaseProvider $infobase;

    public RacSessionProvider $session;

    public RacAgentProvider $agent;


    public function __construct(string $version, string $host = 'localhost', int $port = 1545, string $architecture = 'x64')
    {
        $this->provider = new RacProvider($version, $host, $port, $architecture);
        $this->cluster = new RacClusterProvider($this->provider);
        $this->connection = new RacConnectionProvider($this->provider);
        $this->process = new RacProcessProvider($this->provider);
        $this->server = new RacServerProvider($this->provider);
        $this->infobase = new RacInfobaseProvider($this->provider);
        $this->session = new RacSessionProvider($this->provider);
        $this->agent = new RacAgentProvider($this->provider);
    }

    public function version(): string
    {
        return $this->provider->version();
    }

    public function v(): string
    {
        return '1.0.0';
    }

}