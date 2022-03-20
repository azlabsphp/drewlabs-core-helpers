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
use Drewlabs\Core\Helpers\Contracts\MemoizationOptions;

if (!\defined('__MEMOIZED__NOT_FOUND__')) {
    \define('__MEMOIZED__NOT_FOUND__', '__NOT_FOUND__');
}

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

    /**
     * Function memoization implementation in PHP Language for fast
     * long running pure function or method optimization
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
    public static function memoize(callable $function, $options = null)
    {
        $memoize = new class() {
            /**
             * @var object
             */
            private $cache;

            /**
             * @var callable
             */
            private $internal_;

            public function setEquals(callable $comparator)
            {
                $this->cache->equals = $comparator;

                return $this;
            }

            public function setCacheSize(int $size)
            {
                $this->size = $size;

                return $this;
            }

            public function setCache(object $cache)
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
                    return $this->cache->delete($args);
                }
            }

            public function internal(?callable $function = null)
            {
                if (null !== $function) {
                    $this->internal_ = $function;
                }

                return $this->internal_;
            }

            public function useDefaults()
            {
                // As a default cache we provide an LRU Cache object
                // An LRU cache is used because we assume the recent argument
                // list is subject to be the next to be called by the caller.
                // Therefore we keep the recent callee arguments at the top of
                // the list so that the searching algorithm will perform better
                $this->cache = new class() {
                    public $internal = [];

                    /**
                     * Max size of the LRU cache.
                     *
                     * @var int
                     */
                    public $size = 10;

                    /**
                     * @var \Closure
                     */
                    public $equals;

                    /**
                     * Query for given argument list from cache.
                     *
                     * @param mixed $key
                     *
                     * @return mixed
                     */
                    public function get($key)
                    {
                        $current_ = null;
                        $index = -1;
                        foreach ($this->internal ?? [] as $i => $current) {
                            if (($this->equals)($current->key, $key)) {
                                $current_ = $current;
                                $index = $i;
                                // Check if storage size is greater than the max size
                                // If so, remove the last item from the storage
                                if (\count($this->internal) === $this->size) {
                                    array_pop($this->internal);
                                }
                                break;
                            }
                            ++$index;
                        }
                        if (-1 !== $index && ($index > 0)) {

                            $this->internal = array_merge(
                                [$current_],
                                \array_slice($this->internal, 0, $index),
                                \array_slice($this->internal, $index + 1)
                            );
                        }

                        return $current_ ? $current_->value : __MEMOIZED__NOT_FOUND__;
                    }

                    /**
                     * Add argument list to cache with required computation value.
                     *
                     * @param mixed $key
                     * @param mixed $value
                     *
                     * @return void
                     */
                    public function set($key, $value)
                    {
                        $object = new \stdClass();
                        $object->key = $key;
                        $object->value = $value;
                        $this->internal[] = $object;
                    }

                    /**
                     * Delete/Removes a given argument list from cache.
                     *
                     * @param mixed $key
                     *
                     * @return void
                     */
                    public function delete($key)
                    {
                        $this->internal = Arr::removeWhere($this->internal, function ($value) use ($key) {
                            return ($this->equals)($value->key, $key);
                        }, false);
                    }

                    /**
                     * Clear arguments cache.
                     *
                     * @return array
                     */
                    public function clear()
                    {
                        return $this->internal = [];
                    }
                };
                // Use deep equality comparison by default
                $this->cache->equals = [Comparator::class, 'shallowEqual'];

                return $this;
            }

            #[\ReturnTypeWillChange]
            public function __invoke(array ...$args)
            {
                $args = $args ?? \func_get_args();
                if (__MEMOIZED__NOT_FOUND__ === ($value = $this->cache->get($args))) {
                    $value = ($this->internal_)(...$args);
                    $this->cache->set($args, $value);
                }
                // Returns the computed value on function's call
                return $value;
            }

            public function __call($name, $arguments)
            {
                if (\is_object($this->internal_)) {
                    return $this->internal_->{$name}(...$arguments);
                }
                throw new \BadMethodCallException('Memoized function is not an object, therefore function indirection is not possible');
            }
        };
        // Set the memoized function as internal function
        $memoize->internal($function);
        // Set the default options by default
        $memoize = $memoize->useDefaults();
        if (\is_callable($options)) {
            $memoize = $memoize->setEquals($options);
        }
        if (\is_int($options)) {
            $memoize = $memoize->setCacheSize($options ?? 16);
        }
        if ($options instanceof MemoizationOptions) {
            if (null === ($cache = $options->useCache())) {
                throw new \InvalidArgumentException('Expected $option->useCache() to returns a cache instance, '.\is_object($cache) ? \get_class($cache) : \gettype($cache).' given');
            }
            $memoize = $memoize->setCache($cache);
            $size = $options->cacheSize() ?? 16;
            $memoize = $memoize->setCacheSize($size);
        }

        return $memoize;
    }
}
