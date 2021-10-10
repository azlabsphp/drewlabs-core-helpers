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

if (!function_exists('drewlabs_core_is_callable')) {
    /**
     * This is a PHP 7.4 compatible implementation of is_callable.
     *
     * @param mixed $var
     * @param bool  $syntaxOnly
     *
     * @return bool
     */
    function drewlabs_core_is_callable($var, $syntaxOnly = false)
    {
        if (!is_array($var)) {
            return is_callable($var, $syntaxOnly);
        }

        if ((!isset($var[0]) || !isset($var[1])) ||
            !is_string($var[1] ?? null)
        ) {
            return false;
        }

        if (
            $syntaxOnly &&
            (is_string($var[0]) || is_object($var[0])) &&
            is_string($var[1])
        ) {
            return true;
        }

        $class = is_object($var[0]) ? get_class($var[0]) : $var[0];

        $method = $var[1];

        if (!class_exists($class)) {
            return false;
        }

        if (method_exists($class, $method)) {
            return (new ReflectionMethod($class, $method))->isPublic();
        }

        if (is_object($var[0]) && method_exists($class, '__call')) {
            return (new ReflectionMethod($class, '__call'))->isPublic();
        }

        if (!is_object($var[0]) && method_exists($class, '__callStatic')) {
            return (new ReflectionMethod($class, '__callStatic'))->isPublic();
        }

        return false;
    }
}

if (!function_exists('drewlabs_core_filter_fn_params')) {
    /**
     * Filter paramters of a function based on existance of key in the provided parameter.
     *
     * @param string|array $value
     *
     * @return array
     */
    function drewlabs_core_filter_fn_params($value)
    {
        return static function ($param) use ($value) {
            if (is_array($value)) {
                return !in_array($param, $value, true);
            }

            return $param !== $value;
        };
    }
}


if (!function_exists('filter_fn_params')) {
    /**
     * Filter paramters of a function based on existance of key in the provided parameter
     *
     * @param string|array $value
     * @return array
     */
    function filter_fn_params($value)
    {
        return drewlabs_core_filter_fn_params($value);
    }
}

if (!function_exists('drewlabs_core_is_closure')) {

    /**
     * Determine if the given value is callable, but not a string.
     *
     * @param  mixed  $value
     * @return bool
     */
    function drewlabs_core_is_closure($value)
    {
        return !is_string($value) && is_callable($value);
    }
}
