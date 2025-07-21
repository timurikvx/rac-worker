<?php

namespace RacWorker\Entity;

class AgentEntity extends ClusterAdminEntity
{
    public static function create(string $name, string $description = ''): self
    {
        $admin = new self();
        $admin->setName($name);
        $admin->setDescription($description);
        return $admin;
    }
}