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

if (!function_exists('drewlabs_core_array_sort')) {
    /**
     * Sort a given array using the PHP built-in usort function.
     *
     * @param \Closure|callable $callback
     *
     * @return array
     */
    function drewlabs_core_array_sort(array &$items, $callback)
    {
        usort($items, $callback);

        return $items;
    }
}

if (!function_exists('drewlabs_core_array_is_array_list')) {
    /**
     * Process entries in the provided list and return true if the list is a list of list.
     *
     * @return bool
     */
    function drewlabs_core_array_is_array_list(array $items)
    {
        return array_filter($items, 'is_array') === $items;
    }
}

if (!function_exists('drewlabs_core_array_sort_by')) {
    /**
     * Sort the provided array in the user specified order.
     *
     * @param string $by
     * @param string $order
     */
    function drewlabs_core_array_sort_by(array &$items, $by, $order = DREWLABS_CORE_ORD_ASC)
    {
        $compare = static function ($a, $b) use ($order, $by) {
            // Check first if is standard type in order to avoid error
            if (drewlabs_core_strings_is_str($a) || drewlabs_core_strings_is_str($b)) {
                return drewlabs_core_compare_str($a, $b, $order, $order);
            }
            if (is_numeric($a) || is_numeric($b)) {
                return drewlabs_core_compare_numeric($a, $b, $order);
            }
            // Check if is arrayable
            if (($a instanceof \ArrayAccess || is_array($a)) && ($b instanceof \ArrayAccess || is_array($b))) {
                $a = $a[$by];
                $b = $b[$by];
            }
            // Check if is stdClass type
            if (is_object($a) && is_object($b)) {
                $a = $a->{$by};
                $b = $b->{$by};
            }
            if (drewlabs_core_strings_is_str($a) || drewlabs_core_strings_is_str($b)) {
                return drewlabs_core_compare_str($a, $b, $order);
            }
            if (is_numeric($a) || is_numeric($b)) {
                return drewlabs_core_compare_numeric($a, $b, $order);
            }

            return DREWLABS_CORE_ORD_DESC === $order ? -1 : 1;
        };
        usort($items, $compare);

        return $items;
    }
}

if (!function_exists('drewlabs_core_array_find_index_by')) {
    /**
     * Find index of an array element.
     *
     * @param string $by
     *
     * @return int|null
     */
    function drewlabs_core_array_find_index_by(array $items, $by, $search, $start = null, $end = null): int
    {
        $low = $start ?? 0;
        $high = $end ?? count($items) - 1;

        while ($low <= $high) {
            // code...
            $mid = floor(($low + $high) / 2);
            $searched_item = is_object($items[$mid]) ? $items[$mid]->{$by} : $items[$mid][$by];
            if (drewlabs_core_is_same($searched_item, $search)) {
                return $mid;
            }
            if ($search < $searched_item) {
                $high = $mid - 1;
            } else {
                $low = $mid + 1;
            }
        }

        return -1;
    }
}

if (!function_exists('drewlabs_core_array_combine')) {
    /**
     * Combine values of two array into a single one.
     *
     * @return array
     */
    function drewlabs_core_array_combine(array $lvalue, array $rvalue)
    {
        return array_merge($lvalue, $rvalue);
    }
}

if (!function_exists('drewlabs_core_array_search')) {
    /**
     * PHP search algorithm wrapper
     * It return the index of the matching elements.
     *
     * @param mixed $needle
     * @param bool  $strict_mode
     *
     * @return int|string|bool
     */
    function drewlabs_core_array_search($needle, array $items, $strict_mode = false)
    {
        return array_search($needle, $items, $strict_mode);
    }
}

if (!function_exists('drewlabs_core_array_where')) {
    /**
     * Filter the array using the given callback.
     *
     * @param array $array
     * @param bool  $preserve_keys
     *
     * @return array
     */
    function drewlabs_core_array_where($array, callable $callback, $preserve_keys = true)
    {
        return $preserve_keys ? array_filter($array, $callback, ARRAY_FILTER_USE_BOTH) : array_values(array_filter($array, $callback, ARRAY_FILTER_USE_BOTH));
    }
}

if (!function_exists('drewlabs_core_array_swap')) {

    /**
     * Method for swapping two variables.
     *
     * @param mixed $lhs
     * @param mixed $rhs
     *
     * @return int
     */
    function drewlabs_core_array_swap(&$lhs, &$rhs)
    {
        [$lhs, $rhs] = [$rhs, $lhs];
    }
}

if (!function_exists('drewlabs_core_array_key_exists')) {
    /**
     * Determine if the given key exists in the provided array.
     *
     * @param \ArrayAccess|array $array
     * @param string|int         $key
     *
     * @return bool
     */
    function drewlabs_core_array_key_exists($array, $key)
    {
        if ($array instanceof \ArrayAccess) {
            return $array->offsetExists($key);
        }

        return array_key_exists($key, $array);
    }
}

if (!function_exists('drewlabs_core_array_is_arrayable')) {
    /**
     * Determine whether the given value is array arrayable.
     *
     * @param mixed $value
     *
     * @return bool
     */
    function drewlabs_core_array_is_arrayable($value)
    {
        return is_array($value) || $value instanceof \ArrayAccess;
    }
}

if (!function_exists('drewlabs_core_array_object_to_array')) {
    /**
     * Convert a given object to array.
     *
     * @param mixed $value
     *
     * @return array|null
     */
    function drewlabs_core_array_object_to_array($value)
    {
        $entryToArray = static function ($item) use (&$entryToArray) {
            if (drewlabs_core_array_is_arrayable($item)) {
                return $item;
            }
            if (is_object($item)) {
                $item = (array) $item;
                foreach ($item as $k => $v) {
                    if (is_object($v)) {
                        $item[$k] = $entryToArray($v);
                    }
                }
                return $item;
            }

            return null;
        };
        return $entryToArray($value);
    }
}

if (!function_exists('drewlabs_core_array_has')) {

    /**
     * Check if an array/object has a given property using "dot" notation or not.
     *
     * @param \ArrayAccess|array $array
     * @param string|array       $keys
     *
     * @return bool
     */
    function drewlabs_core_array_has($array, $keys)
    {
        if (null === $keys) {
            return false;
        }

        $keys = (array) $keys;

        if (!$array) {
            return false;
        }

        if ([] === $keys) {
            return false;
        }

        foreach ($keys as $key) {
            $sub_key = $array;

            if (drewlabs_core_array_key_exists($array, $key)) {
                continue;
            }

            foreach (explode('.', $key) as $segment) {
                if (drewlabs_core_array_is_arrayable($sub_key) && drewlabs_core_array_key_exists($sub_key, $segment)) {
                    $sub_key = $sub_key[$segment];
                } else {
                    return false;
                }
            }
        }

        return true;
    }
}

if (!function_exists('drewlabs_core_array_get')) {
    /**
     * Get an item from an array using "dot" notation.
     *
     * @param \ArrayAccess|array $array
     * @param string             $key
     * @param mixed              $default
     *
     * @return mixed
     */
    function drewlabs_core_array_get($array, $key, $default = null)
    {
        if (!drewlabs_core_array_is_arrayable($array)) {
            return $default instanceof \Closure ? $default() : $default;
        }

        if (null === $key) {
            return $array;
        }

        if (drewlabs_core_array_key_exists($array, $key)) {
            return $array[$key];
        }

        if (false === strpos($key, '.')) {
            return $array[$key] ?? ($default instanceof \Closure ? $default() : $default);
        }

        foreach (explode('.', $key) as $segment) {
            if (drewlabs_core_array_is_arrayable($array) && drewlabs_core_array_key_exists($array, $segment)) {
                $array = $array[$segment];
            } else {
                return $default instanceof \Closure ? $default() : $default;
            }
        }

        return $array;
    }
}

if (!function_exists('drewlabs_core_array_set')) {
    /**
     * Set an array item to a given value using "dot" notation.
     *
     * If no key is given to the method, the entire array will be replaced.
     *
     * @param array  $array
     * @param string $key
     * @param mixed  $value
     *
     * @return array
     */
    function drewlabs_core_array_set(&$array, $key, $value)
    {
        if (null === $key) {
            return $array = $value;
        }

        $keys = explode('.', $key);

        while (count($keys) > 1) {
            $key = array_shift($keys);

            // If the key doesn't exist at this depth, we will just create an empty array
            // to hold the next value, allowing us to create the arrays to hold final
            // values at the correct depth. Then we'll keep digging into the array.
            if (!isset($array[$key]) || !is_array($array[$key])) {
                $array[$key] = [];
            }

            $array = &$array[$key];
        }

        $array[array_shift($keys)] = $value;

        return $array;
    }
}

if (!function_exists('drewlabs_core_array_map')) {
    /**
     * Loop through items and return the result of the callback applied to them.
     *
     * @param array $items
     *
     * @return array
     */
    function drewlabs_core_array_map($items, callable $callback)
    {
        $keys = array_keys($items);
        $items = array_map($callback, $items, $keys);

        return array_combine($keys, $items);
    }
}

if (!function_exists('drewlabs_core_array_iter')) {
    /**
     * Loop through a traversable and apply a given callback on each item and return an iterator.
     *
     * @param \Traversable|array $items
     *
     * @return \Iterator
     */
    function drewlabs_core_array_iter($items, callable $callback)
    {
        foreach ($items as $value) {
            // code...
            yield call_user_func($callback, $value);
        }
    }
}

if (!function_exists('drewlabs_core_array_contains_all')) {
    /**
     * Checks if a source array contains all the elements of another array.
     *
     * @return bool
     */
    function drewlabs_core_array_contains_all(array $source, array $innerArray)
    {
        return count(array_intersect($source, $innerArray)) === count($innerArray);
    }
}

if (!function_exists('is_assoc')) {
    /**
     * Checks if an array is an associative array.
     *
     * @deprecated 1.0.0
     *
     * @return bool
     */
    function is_assoc(array $value)
    {
        return array_keys($value) !== range(0, count($value) - 1);
    }
}

if (!function_exists('drewlabs_core_array_is_assoc')) {
    /**
     * Checks if an array is an associative array.
     *
     * @return bool
     */
    function drewlabs_core_array_is_assoc(array $value)
    {
        return array_keys($value) !== range(0, count($value) - 1);
    }
}

if (!function_exists('drewlabs_core_array_group_count')) {
    /**
     * Group array values by the number of their occurence in the array.
     *
     * @return array
     */
    function drewlabs_core_array_group_count(array $array)
    {
        return array_reduce(array_values($array), static function ($carry, $current) {
            return array_merge(
                $carry,
                [
                    $current => array_key_exists($current, $carry) ? $carry[$current] + 1 : 1,
                ]
            );
        }, []);
    }
}

if (!function_exists('drewlabs_core_array_except')) {
    /**
     * Return all items in an array execpt the specified keys.
     *
     * @param string[]|string $keys
     *
     * @return array
     */
    function drewlabs_core_array_except(array $array, $keys)
    {
        drewlabs_core_array_remove($array, $keys);

        return $array;
    }
}

if (!function_exists('drewlabs_core_array_remove')) {

    /**
     * Remove a key or a list of keys from a given array.
     *
     * @param array           $array
     * @param string[]|string $keys
     *
     * @return void
     */
    function drewlabs_core_array_remove(&$array, $keys)
    {
        $original = &$array;
        $keys = (array) $keys;
        if (0 === count($keys)) {
            return;
        }
        foreach ($keys as $key) {
            // if the exact key exists in the top-level, remove it
            if (drewlabs_core_array_key_exists($array, $key)) {
                unset($array[$key]);
                continue;
            }
            $parts = explode('.', $key);
            // clean up before each pass
            $array = &$original;
            while (count($parts) > 1) {
                $part = array_shift($parts);
                if (isset($array[$part]) && is_array($array[$part])) {
                    $array = &$array[$part];
                } else {
                    continue 2;
                }
            }
            unset($array[array_shift($parts)]);
        }
    }
}

if (!function_exists('drewlabs_core_array_udt_to_array')) {
    /**
     * Convert a given user define type to array.
     * 
     * If $preserve_keys = false the first layer of the array is 
     *
     * @param mixed $value
     * @param bool $preserve_keys
     *
     * @return array|null
     */
    function drewlabs_core_array_udt_to_array($value, $preserve_keys = true)
    {
        // $entryToArray = static function ($item) use (&$entryToArray, $preserve_keys) {
        // };
        // return $entryToArray($value);
        if ($value instanceof \Traversable) {
            $value = iterator_to_array($value);
        } elseif ($value instanceof JsonSerializable) {
            $value = (array) $value->jsonSerialize();
        } elseif (is_object($value)) {
            if (method_exists($value, 'all')) {
                $value = $value->all();
            } else if (method_exists($value, 'toArray')) {
                $value = $value->toArray();
            } elseif (method_exists($value, 'toJson')) {
                $value = json_decode($value->toJson(), true);
            } else {
                $value = drewlabs_core_array_object_to_array($value);
            }
        }
        if (!is_array($value)) {
            throw new \InvalidArgumentException('Parameters must of of type array, an \stdClass, an object that define all(), toArray(), toJson() which return arrays, or are instance of' . \Traversable::class . ', ' . \JsonSerializable::class);
        }
        return $preserve_keys ? $value : array_values($value);
    }
}


if (!function_exists('drewlabs_core_array_zip')) {
    /**
     * Zip two or more array values in a single list
     *
     * @param array|mixed $lhs
     * @param array|mixed ...$rhs
     * @return array[]
     */
    function drewlabs_core_array_zip($lhs, ...$rhs)
    {
        $variadic_params = array_slice(func_get_args(), 1);
        $lhs = drewlabs_core_array_udt_to_array($lhs, false);
        $arrayableItems = array_map(function ($item) {
            return drewlabs_core_array_udt_to_array($item, false);
        }, $variadic_params);
        $params = array_merge([function () {
            return func_get_args();
        }, $lhs], $arrayableItems);
        return array_map(...$params);
    }
}


if (!function_exists('drewlabs_core_array_szip')) {
    /**
     * Zip two or more array values in a single list
     * 
     * Arrays passed as paramter must of of the same size as the first array
     *
     * @param array|mixed $lhs
     * @param array|mixed ...$rhs
     * @return array[]
     */
    function drewlabs_core_array_szip($lhs, ...$rhs)
    {
        $variadic_params = array_slice(func_get_args(), 1);
        $lhs = drewlabs_core_array_udt_to_array($lhs, false);
        $count = count($lhs);
        // Transform all variadic params to array to ensure data integrity
        $arrayableItems = array_map(function ($item) {
            return drewlabs_core_array_udt_to_array($item, false);
        }, $variadic_params);
        // Ensure that all arrays are of the same size
        $all_same_size = array_filter($arrayableItems, function ($v) use ($count) {
            return count($v) === $count;
        }) === $arrayableItems;
        if (!$all_same_size) {
            throw new \InvalidArgumentException('All params must be of the same size');
        }
        return drewlabs_core_array_zip($lhs, ...$rhs);
    }
}
