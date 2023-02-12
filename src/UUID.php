<?php

declare(strict_types=1);

/*
 * This file is part of the Drewlabs package.
 *
 * (c) Sidoine Azandrew <azandrewdevelopper@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Drewlabs\Core\Helpers;

final class UUID
{
    /**
     * @throws \ReflectionException
     * @throws \Exception
     *
     * @return string
     */
    public static function create(callable $factory = null)
    {
        if ($factory) {
            return (string) \call_user_func($factory);
        }
        if (class_exists('Ramsey\\Uuid\\Uuid')) {
            return (string) (new \ReflectionMethod('Ramsey\\Uuid\\Uuid', 'uuid4'))->invoke(null, []);
        }
        if (\function_exists('com_create_guid')) {
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
     * @throws \Exception
     *
     * @return string|mixed
     */
    public static function ordered(callable $factory = null)
    {
        if ($factory) {
            return \call_user_func($factory);
        }
        if (!class_exists('Ramsey\\Uuid\\UuidFactory')) {
            throw new \Exception(sprintf('%s required the ramsey/uuid library', __FUNCTION__));
        }
        $factoryClazz = 'Ramsey\\Uuid\\UuidFactory';
        $factory = new $factoryClazz();
        if (!class_exists('Ramsey\\Uuid\\Generator\\CombGenerator')) {
            throw new \Exception(sprintf('%s required the ramsey/uuid library', __FUNCTION__));
        }
        $generatorClazz = 'Ramsey\\Uuid\\Generator\\CombGenerator';
        $factory->setRandomGenerator(new $generatorClazz(
            $factory->getRandomGenerator(),
            $factory->getNumberConverter()
        ));

        if (!class_exists('Ramsey\\Uuid\\Codec\\TimestampFirstCombCodec')) {
            throw new \Exception(sprintf('%s required the ramsey/uuid library', __FUNCTION__));
        }
        $codecClazz = 'Ramsey\\Uuid\\Codec\\TimestampFirstCombCodec';
        $factory->setCodec(new $codecClazz(
            $factory->getUuidBuilder()
        ));

        return (string) $factory->uuid4();
    }

    /**
     * @throws \ReflectionException
     * @throws \Exception
     *
     * @return string|mixed
     */
    public static function createUsing(callable $factory = null)
    {
        return (static function () use ($factory) {
            return (string) static::create($factory);
        })();
    }
}
