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

use Drewlabs\Core\Helpers\Rand;
use Drewlabs\Core\Helpers\UUID;

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
        return Rand::key($length);
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
        return Rand::dateTime($added_value, $date);
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
        return Rand::str($n);
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
        return Rand::secret($it);
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
        return Rand::int($min, $max);
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
        return UUID::guid($factory);
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
        return UUID::orderedUUID($factory);
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
        return UUID::createUUIDUsing($factory);
    }
}
