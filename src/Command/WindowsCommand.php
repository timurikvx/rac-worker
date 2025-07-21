<?php

namespace RacWorker\Command;

class WindowsCommand
{
    public function execute($command): string|false|null
    {
        return shell_exec('chcp 65001 > nul && '.$command);
    }
}