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
     * @param bool $preserve_keys
     *
     * @return ArrayIterator
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
     * @param mixed|null $initial_value
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
     * @param bool $preserve_keys
     * @param int  $flags         // Indicates whether to use keys or
     *                            both $key and value in the filter function
     *
     * @return Iterator
     */
    function drewlabs_core_iter_filter(
        Iterator $it,
        Closure $filterFn,
        $preserve_keys = true,
        $flags = \ARRAY_FILTER_USE_BOTH
    ) {
        $out = [];
        iterator_apply(
            $it,
            static function (Iterator $it) use ($filterFn, &$out, $preserve_keys, $flags) {
                [$current, $key] = [$it->current(), $it->key()];
                $result = \ARRAY_FILTER_USE_BOTH === $flags ? $filterFn($current, $key) : $filterFn($key);
                if (!$result) {
                    return true;
                }
                if ($preserve_keys) {
                    $out[$key] = $current;
                } else {
                    $out[$key] = $current;
                }

                return true;
            },
            [$it]
        );

        return new ArrayIterator($out);
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
     * @return Iterator
     */
    function drewlabs_core_iter_only(Iterator $list, $keys = [], bool $use_keys = true)
    {
        if (!is_string($keys) && !is_array($keys) && !($keys instanceof Iterator)) {
            throw new InvalidArgumentException('$keys parameter must be a PHP string|array or a validate iterator');
        }
        $keys = is_string($keys) ? [$keys] : (is_array($keys) ? $keys : iterator_to_array($keys));

        return drewlabs_core_iter_filter(
            $list,
            static function ($current) use ($keys) {
                return in_array($current, $keys, true);
            },
            true,
            $use_keys ? \ARRAY_FILTER_USE_KEY : \ARRAY_FILTER_USE_BOTH
        );
    }
}

if (!function_exists('drewlabs_core_iter_collapse')) {
    /**
     * Collapse an array of arrays into a single array.
     *
     * @param \Iterable $list
     *
     * @return array
     */
    function drewlabs_core_iter_collapse($list)
    {
        $results = [];
        foreach ($list as $value) {
            if (is_object($value) && method_exists($value, 'all')) {
                $value = $value->all();
            } elseif (!is_array($value)) {
                continue;
            }
            $results[] = $value;
        }

        return array_merge([], ...$results);
    }
}
