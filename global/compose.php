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

if (!function_exists('drewlabs_core_fn_compose')) {
    /**
     * Function composition function that apply transformations to the source input in the top -> down
     * level that the functions appear.
     * It decorates an inner function that accept only single values, and threat array
     * params as single value. To pass list of parameters as array, use {drewlabs_core_fn_compose_array}.
     *
     * @param \Closure[] ...$funcs
     *
     * @return \Closure|callable
     */
    function drewlabs_core_fn_compose(...$funcs)
    {
        return static function ($source) use ($funcs) {
            return array_reduce(
                $funcs,
                static function ($carry, $func) {
                    return $func($carry);
                },
                $source
            );
        };
    }
}

if (!function_exists('drewlabs_core_fn_compose_array')) {
    /**
     * Function composition function that apply transformations to the source input in the top -> down
     * level that the functions appear.
     * This method decorate an variadic inner function that one or many parameter or an array or parameter
     * If should call with single value, use {drewlabs_core_fn_compose} which does not decorate an inner
     * variadic function and accept only single values, and threat array params as single value.
     *
     * @param \Closure[] ...$funcs
     *
     * @return \Closure|callable
     */
    function drewlabs_core_fn_compose_array(...$funcs)
    {
        return static function (...$source) use ($funcs) {
            return array_reduce(
                $funcs,
                static function ($carry, $func) {
                    return $func($carry);
                },
                $source
            );
        };
    }
}

if (!function_exists('drewlabs_core_fn_reverse_compose')) {
    /**
     * Function composition function that apply transformations to the source input in the bottom -> up order
     * in which functions appear.
     *
     * Use {drewlabs_core_fn_reverse_compose_array} instead for multiple parameter value or multiple parameter as array.
     *
     * @param \Closure[] ...$funcs
     *
     * @return \Closure|callable
     */
    function drewlabs_core_fn_reverse_compose(...$funcs)
    {
        return drewlabs_core_fn_compose(...array_reverse($funcs));
    }
}

if (!function_exists('drewlabs_core_fn_reverse_compose_array')) {
    /**
     * Function composition function that apply transformations to the source input in the bottom -> up order
     * in which functions appear.
     *
     * This method decorate an variadic inner function that take one or many parameter or an array or parameters.
     * Use {drewlabs_core_fn_reverse_compose} instead for single parameter value.
     *
     * @param \Closure[] ...$funcs
     *
     * @return \Closure|callable
     */
    function drewlabs_core_fn_reverse_compose_array(...$funcs)
    {
        return drewlabs_core_fn_compose_array(...array_reverse($funcs));
    }
}
