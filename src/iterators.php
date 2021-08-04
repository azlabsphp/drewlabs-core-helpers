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

if (!function_exists('drewlabs_core_iter_map')) {
    /**
     * Map through the values of a given iterator.
     *
     * @param \Closure $callback
     *
     * @return \Iterator|\ArrayIterator|array
     */
    function drewlabs_core_iter_map(Iterator $it, Closure $callback, $preserve_keys = true)
    {
        $items = [];
        $keys = [];
        iterator_apply($it, static function (Iterator $it) use ($callback, &$items, &$keys, $preserve_keys) {
            [$current, $key] = [$it->current(), $it->key()];
            $items[] = $callback($current, $key);
            if ($preserve_keys) {
                $keys[] = $key;
            }

            return true;
        }, [$it]);

        return new ArrayIterator($preserve_keys ? array_combine($keys, $items) : $items);
    }
}

if (!function_exists('drewlabs_core_iter_reduce')) {
    /**
     * Apply a reducer to the values of a given iterator.
     *
     * @return mixed
     */
    function drewlabs_core_iter_reduce(Iterator $it, Closure $reducer, $initial_value = null)
    {
        $out = $initial_value;
        iterator_apply($it, static function (Iterator $it) use ($reducer, &$out) {
            [$current, $key] = [$it->current(), $it->key()];
            $out = $reducer($out, $current, $key);

            return true;
        }, [$it]);

        return $out;
    }
}

if (!function_exists('drewlabs_core_iter_filter')) {
     /**
      * Apply a filter to the values of a given iterator.
      *
      * @param Iterator $it
      * @param Closure $filterFn
      * @return Iterator
      */
    function drewlabs_core_iter_filter(Iterator $it, Closure $filterFn)
    {
        $out = [];
        iterator_apply($it, static function (Iterator $it) use ($filterFn, &$out) {
            [$current, $key] = [$it->current(), $it->key()];
            if ($filterFn($current, $key)) {
                $out[] = $current;
            }
            return true;
        }, [$it]);

        return new ArrayIterator($out);
    }
}
