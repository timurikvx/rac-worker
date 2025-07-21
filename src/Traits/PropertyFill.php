<?php

namespace RacWorker\Traits;

trait PropertyFill
{
    public function fill(array $properties): void
    {
        $debug = ($this::class == 'RacWorker\Entity\ConnectionEntity');
        $collection = collect($properties);
        try {
            $reflection = new \ReflectionClass($this::class);
        }catch (\ReflectionException $e) {
            return;
        }
        $all = $reflection->getProperties();
        foreach ($all as $property) {
            $name = $property->getName();
            $value = $collection->get($this->toSnakeCase($name));
            if(is_null($value)) {
                continue;
            }
            $this->set($name, $value);
            //$this->$name = $value;
        }
    }

    private function toSnakeCase(string $string): string
    {
        $parts = preg_split('/(?=[A-Z0-9])/', $string);
        return strtolower(implode('-', $parts));
    }

}