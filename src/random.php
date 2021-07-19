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

if (!function_exists('drewlabs_core_random_app_key')) {
    /**
     * Generate a random api key.
     *
     * @param int $length
     *
     * @return string
     */
    function drewlabs_core_random_app_key($length)
    {
        return str_replace('=', '', str_replace([chr(92), '+', chr(47), chr(38)], '.', base64_encode(openssl_random_pseudo_bytes($length))));
    }
}

if (!function_exists('drewlabs_core_random_date_time')) {

    /**
     * Generate a new date with added value.
     *
     * @param mixed $added_value
     * @param bool  $date
     *
     * @return string
     */
    function drewlabs_core_random_date_time($added_value, $date = false)
    {
        $timestamp = strtotime($added_value, time());

        return true === $date ? date('Y-m-d', $timestamp) : date('Y-m-d H:i:s', $timestamp);
    }
}

if (!function_exists('drewlabs_core_random_sub_str')) {
    /**
     * Generate random string with a specified length.
     *
     * @return string
     */
    function drewlabs_core_random_sub_str(int $n)
    {
        $characters = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '0', '9', '8', '7', '6', '5', '4', '3', '2', '1', '_', '&', '$', '@', '!', '?', ')', '(', '+'];
        $start = random_int(1, count($characters));
        shuffle($characters);
        $str = implode('', $characters);

        return substr($str, $start, $n);
    }
}

if (!function_exists('drewlabs_core_random_password')) {
    /**
     * Simple function for generating random password.
     *
     * @return string
     */
    function drewlabs_core_random_password(int $it = 4)
    {
        $tmpstr = '';
        for ($i = $it; $i > 0; --$i) {
            $tmpstr .= drewlabs_core_random_sub_str($i);
        }

        return $tmpstr;
    }
}

if (!function_exists('drewlabs_core_random_int')) {

    /**
     * Generate a random integer between a minimum and a maximum values.
     *
     * @return int
     */
    function drewlabs_core_random_int(int $min, int $max)
    {
        return function_exists('random_int') ? random_int($min, $max) : random_int($min, $max);
    }
}

if (!function_exists('drewlabs_core_random_guid')) {
    /**
     * Generate a Global unique identifier (GUID) (version 4).
     *
     * @return string|mixed
     */
    function drewlabs_core_random_guid($factory = null)
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
}

if (!function_exists('drewlabs_core_random_ordered_uuid')) {
    /**
     * Generate a time-ordered Global unique identifier (version 4).
     *
     * @return string|mixed
     */
    function drewlabs_core_random_ordered_uuid($factory = null)
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
}

if (!function_exists('drewlabs_core_random_create_uuids_using')) {
    /**
     * Generate a GUuid using the provided callable.
     *
     * @return string|mixed
     */
    function drewlabs_core_random_create_uuids_using(?callable $factory = null)
    {
        return (static function () use ($factory) {
            return (string) drewlabs_core_random_guid($factory);
        })();
    }
}
