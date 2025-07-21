<?php

namespace RacWorker\Command;

class Command
{

    public function __construct()
    {

    }
    public function run($command, &$code = -1): array
    {
        $output = [];
        exec($command, $output, $code);
        return $output;
    }

    public function execute($command, &$error = ''): string|false|null|array
    {
        $os = php_uname('s');
        $output = '';
        $error = '';
        if($os == 'Linux'){
            return shell_exec('LC_ALL=en_US.UTF-8 ' . $command);
        }else{
            $descriptors = [
                ['pipe', 'r'],
                ['pipe', 'w'],
                ['pipe', 'w']
            ];
            $pipes = [];
            $process = proc_open($command, $descriptors, $pipes);
            if(is_resource($process)){
                $output = iconv('CP866', 'UTF-8//IGNORE', stream_get_contents($pipes[1]));
                $error = str_replace('"', '', trim(iconv('CP866', 'UTF-8//IGNORE', stream_get_contents($pipes[2]))));
            }
            //exec($command, $output, $code);//'chcp 65001 > nul && '.
            return explode("\r\n", $output);
        }
    }

}