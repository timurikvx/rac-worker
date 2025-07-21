<?php

namespace RacWorker\Services;

use RacWorker\Command\Command;
use RacWorker\RacArchitecture;

class RacProvider
{

    private string $os;

    private Command $command;

    private string $version;

    private string $architecture;

    private string|null $path1C;

    private string $filler = '';

    private string $host;

    private int $port;

    public function __construct(string $version, string $host = 'localhost', int $port = 1545, string $architecture = RacArchitecture::X64)
    {
        $this->command = new Command();
        $this->version = $version;
        $this->architecture = $architecture;
        $this->host = $host;
        $this->port = $port;
        $this->os = php_uname('s');
        $this->path1C = $this->getPath1C();
        if($this->os === 'Windows NT'){
            $this->filler = '"';
        }
    }

    public function getHost(): string
    {
        return $this->host.':'.$this->port;
    }

    public function getPath(): string|null
    {
        return $this->path1C;
    }

    private function getPath1C(): string|null
    {
        $windows = '';
        $linux = '';
        if($this->architecture == RacArchitecture::X64){
            $windows = 'C:/Program Files/1cv8/';
            $linux = '/opt/1cv8/x64/';
        }

        if($this->architecture == RacArchitecture::X86_64){
            $windows = 'C:/Program Files (x86)/1cv8/';
            $linux = '/opt/1cv8/x86_64/';
        }
        if($this->os == 'Linux'){
            return $linux.$this->version;
        }
        if($this->os == 'Windows NT'){
            return $windows.$this->version.'/bin';
        }
        return null;
    }

    public function version(&$error = ''): string
    {
        $command = '--version';
        $array = $this->execute($command, $error);
        if(count($array) > 0){
            return $array[0];
        }
        return '';
    }

    public function execute(string $command, &$error = ''): array
    {
        $path = $this->getPath().'/rac';
        $shell = $this->filler.$path.$this->filler.' '.$command;
        $result = $this->command->execute($shell, $error);
        if(is_null($result)){
            return [];
        }
        return $result; //trim($result);
    }

    public function handle(array $array, array $properties): array //string $string,
    {
        //$array = explode("\n", $string);
        $all = [];
        $data = [];
        foreach ($array as $row) {
            if(empty($row)){
                $item = $this->handleProperties($data, $properties);
                if(empty($item)){
                    break;
                }
                $all[] = $item;
                $data = [];
                continue;
            }
            $options = explode(": ", $row);
            $value = (count($options) == 1)? '': $options[1];
            $data[trim($options[0])] = trim($value);
        }
        if(!empty($data)){
            $all[] = $this->handleProperties($data, $properties);
        }
        return $all;
    }

    public function handleProperties(array $values, array $properties): array
    {
        $handles = [];
        foreach ($values as $property => $value) {
            if(!key_exists($property, $properties)){
                continue;
            }
            $handles[$property] = $properties[$property]($value);
        }
        return $handles;
    }

}