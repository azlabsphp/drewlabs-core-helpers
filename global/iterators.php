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

use Drewlabs\Core\Helpers\Iter;

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
     * @param bool $preserveKeys
     *
     * @return Traversable
     */
    function drewlabs_core_iter_map(Iterator $iterator, Closure $callback, $preserveKeys = true)
    {
        return Iter::map($iterator, $callback, $preserveKeys);
    }
}

if (!function_exists('drewlabs_core_iter_reduce')) {
    /**
     * Apply a reducer to the values of a given iterator.
     *
     * @param mixed $initial
     *
     * @return mixed
     */
    function drewlabs_core_iter_reduce(Iterator $iterator, Closure $reducer, $initial = null)
    {
        return Iter::reduce($iterator, $reducer, $initial);
    }
}

if (!function_exists('drewlabs_core_iter_filter')) {

    /**
     * Apply a filter to the values of a given iterator.
     *
     * @param bool $preserveKeys
     * @param int  $flags        // Indicates whether to use keys or
     *                           both $key and value in the filter function
     *
     * @return Traversable
     */
    function drewlabs_core_iter_filter(
        Iterator $iterator,
        Closure $predicate,
        $preserveKeys = true,
        $flags = \ARRAY_FILTER_USE_BOTH
    ) {
        return Iter::filter($iterator, $predicate, $preserveKeys, $flags);
    }
}

if (!function_exists('drewlabs_core_iter_only')) {

    /**
     * Filter iterator returning only the values matching the provided keys.
     *
     * @param array $keys
     *
     * @throws InvalidArgumentException
     *
     * @return Traversable
     */
    function drewlabs_core_iter_only(Iterator $iterator, $keys = [], bool $useKeys = true)
    {
        return Iter::only($iterator, $keys, $useKeys);
    }
}

if (!function_exists('drewlabs_core_iter_collapse')) {
    /**
     * Collapse an array of arrays into a single array.
     *
     * @param mixed $value
     *
     * @return array
     */
    function drewlabs_core_iter_collapse($value)
    {
        return Iter::collapse($value);
    }
}
