<?php

namespace App\Helper;

use ReflectionClass;

class HydratorHelper
{
    /**
     * @param object $from
     * @param class-string $toClass
     * @return mixed
     */
    public static function convertClassNamingConvention(object $from, string $toClass): mixed
    {
        $to = new $toClass();
        return self::updateObjectNamingConvention($from, $to);
    }

    public static function updateObjectNamingConvention(object $from, object $to): mixed
    {
        $reflectionClass = new ReflectionClass($from);
        $properties = $reflectionClass->getProperties();
        foreach ($properties as $property) {
            $propertyName = $property->getName();
            $maj = ucfirst($propertyName);
            $getter = 'get' . $maj;
            $setter = 'set' . $maj;
            if (method_exists($to, $setter)) {
                $to->$setter($from->$getter());
            }
        }
        return $to;
    }
}
