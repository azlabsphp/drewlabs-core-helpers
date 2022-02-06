<?php

namespace Drewlabs\Core\Helpers;

use Exception;
use ReflectionException;
use ReflectionMethod;

class UUID
{
    /**
     * 
     * @param null|callable $factory 
     * @return string 
     * @throws ReflectionException 
     * @throws Exception 
     */
    public static function guid(?callable $factory = null)
    {
        if ($factory) {
            return (string) call_user_func($factory);
        }
        if (class_exists('Ramsey\\Uuid\\Uuid')) {
            return (string) (new ReflectionMethod('Ramsey\\Uuid\\Uuid', 'uuid4'))->invoke(null, []);
        }
        if (function_exists('com_create_guid')) {
            return trim(com_create_guid(), '{}');
        }

        return sprintf(
            '%04X%04X-%04X-%04X-%04X-%04X%04X%04X',
            random_int(0, 65535),
            random_int(0, 65535),
            random_int(0, 65535),
            random_int(16384, 20479),
            random_int(32768, 49151),
            random_int(0, 65535),
            random_int(0, 65535),
            random_int(0, 65535)
        );
    }

    /**
     * 
     * @param callable $factory 
     * @return mixed 
     * @throws Exception 
     */
    public static function orderedUUID(?callable $factory = null)
    {
        if ($factory) {
            return call_user_func($factory);
        }
        if (!class_exists('Ramsey\\Uuid\\UuidFactory')) {
            throw new Exception(sprintf('%s required the ramsey/uuid library', __FUNCTION__));
        }
        $factoryClazz = 'Ramsey\\Uuid\\UuidFactory';
        $factory = new $factoryClazz();
        if (!class_exists('Ramsey\\Uuid\\Generator\\CombGenerator')) {
            throw new Exception(sprintf('%s required the ramsey/uuid library', __FUNCTION__));
        }
        $generatorClazz = 'Ramsey\\Uuid\\Generator\\CombGenerator';
        $factory->setRandomGenerator(new $generatorClazz(
            $factory->getRandomGenerator(),
            $factory->getNumberConverter()
        ));

        if (!class_exists('Ramsey\\Uuid\\Codec\\TimestampFirstCombCodec')) {
            throw new Exception(sprintf('%s required the ramsey/uuid library', __FUNCTION__));
        }
        $codecClazz = 'Ramsey\\Uuid\\Codec\\TimestampFirstCombCodec';
        $factory->setCodec(new $codecClazz(
            $factory->getUuidBuilder()
        ));

        return (string) $factory->uuid4();
    }

    /**
     * 
     * @param null|callable $factory 
     * @return mixed 
     * @throws ReflectionException 
     * @throws Exception 
     */
    public static function createUUIDUsing(?callable $factory = null)
    {
        return (static function () use ($factory) {
            return (string) static::guid($factory);
        })();
    }
}
