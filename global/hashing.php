<?php

declare(strict_types=1);

use Drewlabs\Core\Helpers\Str;

/*
 * This file is part of the Drewlabs package.
 *
 * (c) Sidoine Azandrew <azandrewdevelopper@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

if (!function_exists('drewlabs_core_hashing_base62encode')) {

    /**
     * 
     * @param string $value 
     * @return string 
     */
    function drewlabs_core_hashing_base62encode($value)
    {
        return Str::base62encode($value);
    }
}

if (!function_exists('drewlabs_core_hashing_base62decode')) {

    /**
     * 
     * @param string $value 
     * @return string 
     */
    function drewlabs_core_hashing_base62decode($value)
    {
        return Str::base62decode($value);
    }
}

if (!function_exists('drewlabs_core_hashing_hash_str')) {
    /**
     * Creates a hash value from the provided string.
     *
     * @param string   $source
     * @param \Closure $keyResolver
     *
     * @return string
     */
    function drewlabs_core_hashing_hash_str($source, Closure $keyResolver)
    {
        return Str::hash($source, $keyResolver);
    }
}

if (!function_exists('drewlabs_core_hashing_hash_str_compare')) {
    /**
     * Compare the has value of the source string against the user provided hash.
     *
     * @param string          $source
     * @param string          $match
     * @param \Closure|string $keyResolver
     *
     * @return bool
     */
    function drewlabs_core_hashing_hash_str_compare($source, $match, $keyResolver)
    {
        return Str::hequals(Str::hash($source, $keyResolver), $match);
    }
}
