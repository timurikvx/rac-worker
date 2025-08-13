<?php

namespace RacWorker\Factory;

class ClassFiller
{

    public static function list(string $className, $array, $dependencies = []): array
    {
        $list = [];
        foreach ($array as $item) {
            $item = self::item($className, $item, $dependencies);
            $list[] = $item;
        }
        return $list;
    }

    public static function item(string $className, $array, $dependencies = []): object|null
    {
        try{
            $reflection = new \ReflectionClass($className);
        }catch (\Throwable $ex){
            return null;
        }
        $properties = collect(array_merge($array, $dependencies));
        $class = self::resolveClass($reflection, $properties);
        if(!is_null($class)){
            //self::fillClass($class, $reflection, $properties);
            $class->fill($array);
        }
        return $class;
    }

    private static function resolveClass(\ReflectionClass $reflection, $properties): object|null
    {
        $debug = $reflection->getName() == 'RacWorker\Entity\ConnectionEntity';
        $constructor = $reflection->getConstructor();
        $className = $reflection->getName();
        if(is_null($constructor)){
            return new $className();
        }
        $parameters = $constructor->getParameters();
        if(count($parameters) == 0){
            return new $className();
        }
        $values = [];

        foreach ($parameters as $parameter){
            $name = self::toSnakeCase($parameter->getName());
            $value = $properties->get($name);
            if(is_null($value)){
                try{
                    $value = $parameter->getDefaultValue();
                }catch (\Throwable $exception){
                    continue;
                }
            }
            $values[] = $value;
        }

        try{
            return new $className(...$values);
        }catch (\Throwable $exception){
            return null;
        }
    }

    private static function fillClass(object &$class, \ReflectionClass $reflection, object $properties): void
    {
        foreach ($properties as $field => $value) {
            $methodName = 'set'.ucfirst(str_replace(' ', '', ucwords(str_replace('-', ' ', $field))));
            try {
                $method = $reflection->getMethod($methodName);
            }catch (\Throwable $exception){
                continue;
            }
            $callable = $method->getName();
            $class->$callable($value);
        }
    }

    private static function toSnakeCase(string $string): string
    {
        $parts = preg_split('/(?=[A-Z])/', $string);
        return strtolower(implode('-', $parts));
    }

}