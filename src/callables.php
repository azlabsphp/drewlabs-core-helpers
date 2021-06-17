<?php

if (!function_exists('drewlabs_core_is_callable')) {
    /**
     * This is a PHP 7.4 compatible implementation of is_callable.
     *
     * @param  mixed  $var
     * @param  bool  $syntaxOnly
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
     * Filter paramters of a function based on existance of key in the provided parameter
     *
     * @param string|array $value
     * @return array
     */
    function drewlabs_core_filter_fn_params($value)
    {
        return function ($param) use ($value) {
            if (is_array($value)) {
                return !in_array($param, $value);
            }
            return $param !== $value;
        };
    }
}