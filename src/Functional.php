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
use Drewlabs\Caching\Contracts\CacheInterface;
use Drewlabs\Caching\LRUCache;
use Drewlabs\Caching\Tokens;
use Drewlabs\Core\Helpers\Contracts\MemoizationOptions;
use Drewlabs\Caching\Contracts\BufferedCacheInterface;
use Drewlabs\Caching\Contracts\ProvidesPredicate;


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
     * @param mixed             $value
     * @param \Closure|callable $callback
     *
     * @return mixed
     */
    public static function tap($value, $callback)
    {
        $callback(
            \is_object($state = (\is_callable($value) && !\is_string($value) ?
                \call_user_func($value) : $value)) ? clone $state : $state
        );

        return $value;
    }

    /**
     * Function memoization memoization implementation in PHP Language for fast call
     * of long running pure functions or methods
     * It uses an internal LRU Caching system for algorithm optimization.
     *
     * ```php
     * <?php
     *
     *  function longComputation()
     *  {
     *      printf("\nCalling long compution...\n");
     *      sleep(1);
     *      return array_reduce([1, 2, 4, 5, 6, 7], function ($carry, $curr) {
     *          $carry += $curr;
     *          return $carry;
     *      }, 0);
     *  }
     *  // Memoizing the longComputation function
     *  $memo = Functional::memoize('longComputation');
     *
     *  // Calling the memoized function
     *  $memo();
     *```
     *
     * @param int|MemoizationOptions|callable|\Closure $options
     *
     * @return #Class#1cda036d
     */
    public static function memoize($function, $options = null)
    {
        $memoize = new class()
        {
            /**
             * @var BufferedCacheInterface&ProvidesPredicate
             */
            private $cache;

            /**
             * @var callable
             */
            private $callback;

            public function setPredicate($comparator)
            {
                $this->cache->setPredicate($comparator);
                return $this;
            }

            public function setCacheSize(int $size)
            {
                $this->cache->setCapacity($size);
                return $this;
            }

            public function setCache(CacheInterface $cache)
            {
                if ($cache) {
                    $this->cache = $cache;
                }
                return $this;
            }

            public function clear()
            {
                if ($this->cache) {
                    return $this->cache->clear();
                }
            }

            public function remove(...$args)
            {
                if ($this->cache) {
                    return $this->cache->remove($args);
                }
            }

            public function setCallback($function = null)
            {
                if (null !== $function) {
                    $this->callback = $function;
                }
                return $this->callback;
            }

            public function useDefaults()
            {
                // As a default cache we provide an LRU Cache object
                // An LRU cache is used because we assume the recent argument
                // list is subject to be the next to be called by the caller.
                // Therefore we keep the recent callee arguments at the top of
                // the list so that the searching algorithm will perform better
                // Use deep equality comparison by default
                /**
                 * @var BufferedCacheInterface $cache
                 */
                $this->cache = new LRUCache([Comparator::class, 'shallowEqual']);
                return $this;
            }

            #[\ReturnTypeWillChange]
            public function __invoke(...$args)
            {
                $args = $args ?? \func_get_args();
                if (Tokens::__MEMOIZED__NOT_FOUND__ === ($value = $this->cache->get($args))) {
                    $this->cache->set($args, $value = ($this->callback)(...$args));
                }
                // Returns the computed value on function's call
                return $value;
            }

            public function __call($name, $arguments)
            {
                if (\is_object($this->callback)) {
                    return $this->callback->{$name}(...$arguments);
                }
                throw new \BadMethodCallException('Memoized function is not an object, therefore function indirection is not possible');
            }
        };
        // Set the default options by default
        $memoize = $memoize->useDefaults();
        // Set the memoized function as internal function
        $memoize->setCallback($function);

        // Set the predicate function if the options is a callable instance
        if (\is_callable($options)) {
            $memoize = $memoize->setPredicate($options);
        }

        // Set the cache size if the options is an int
        if (\is_int($options)) {
            $memoize = $memoize->setCacheSize($options ?? 16);
        }

        // Case the options parameter is a memoizationOptions instance, set the cache instance and the cache size
        if ($options instanceof MemoizationOptions) {
            if (null !== ($cache = $options->useCache())) {
                $memoize = $memoize->setCache($cache);
            }
            $memoize = $memoize->setCacheSize($options->cacheSize() ?? 16);
        }

        return $memoize;
    }
}
