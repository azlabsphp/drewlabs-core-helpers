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

use Closure;

class Functional
{
    /**
     * Function composition function that apply transformations to the source input in the top -> down
     * level that the functions appear.
     *
     * It decorates an inner function that accept only single values, and threat array
     * params as single value. To pass list of parameters as array, use {Functional::vcompose}.
     *
     * @param \Closure[] ...$funcs
     *
     * @return \Closure|callable
     */
    public static function compose(...$funcs)
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

    /**
     * Function composition function that apply transformations to the source input in the top -> down
     * level that the functions appear.
     *
     * This method decorate a variadic inner function that one or many parameter or an array or parameter
     * If should call with single value, use {Functional::compose} which does not decorate an inner
     * variadic function and accept only single values, and threat array params as single value.
     *
     * @param \Closure[] ...$funcs
     *
     * @return \Closure|callable
     */
    public static function vcompose(...$funcs)
    {
        return static function (...$source) use ($funcs) {
            return array_reduce(
                \array_slice($funcs, 1),
                static function ($carry, $func) {
                    return $func($carry);
                },
                $funcs[0](...$source)
            );
        };
    }

    /**
     * Function composition function that apply transformations to the source input in the bottom -> up order
     * in which functions appear.
     *
     * Use {Functional::rvcompose} instead for multiple parameter value or multiple parameter as array.
     *
     * @param \Closure[] ...$funcs
     *
     * @return \Closure|callable
     */
    public static function rcompose(...$funcs)
    {
        return static::compose(...array_reverse($funcs));
    }

    /**
     * Function composition function that apply transformations to the source input in the bottom -> up order
     * in which functions appear.
     *
     * This method decorate an variadic inner function that take one or many parameter or an array or parameters.
     * Use {Functional::rcompose} instead for single parameter value.
     *
     * @param \Closure[] ...$funcs
     *
     * @return \Closure|callable
     */
    public static function rvcompose(...$funcs)
    {
        return static::vcompose(...array_reverse($funcs));
    }

    /**
     * This is a PHP 7.4 compatible implementation of is_callable.
     *
     * @param mixed $var
     * @param bool  $syntax_only
     *
     * @return bool
     */
    public static function isCallable($var, $syntax_only = false)
    {
        if (!\is_array($var)) {
            return \is_callable($var, $syntax_only);
        }

        if (\count(array_filter($var)) < 1) {
            return false;
        }
        if (!\is_string($class = \is_object($var[0]) ? \get_class($var[0]) : $var[0])) {
            return false;
        }
        $method = $var[1] ?? '__invoke';
        try {
            return (new \ReflectionMethod($class, $method))->isPublic();
        } catch (\ReflectionException $e) {
            return false;
        }

        return false;
    }

    /**
     * Call a user defined closure|function on the provided source parameter.
     *
     * Note: It internally call PHP {@see clone} function if the state of the source
     * object is an object in order to pass a copy of it to the {@see $callback}.
     * It does not guaranty a deep copy as the developper must make sure clone object
     * is purely immutable.
     *
     * @param mixed             $source
     * @param \Closure|callable $callback
     *
     * @return mixed
     */
    public static function tap($source, callable $callback)
    {
        $callback(
            \is_object($state = (\is_callable($source) && !\is_string($source) ?
                \call_user_func($source) : $source)) ? clone $state : $state
        );
    }
}
