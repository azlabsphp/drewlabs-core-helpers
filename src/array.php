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

use Drewlabs\Core\Helpers\Arr;
use Drewlabs\Core\Helpers\Arrays\BinarySearchResult;

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
        return Arr::sort($items, $callback);
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
        return Arr::isList($items);
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
        return Arr::sortBy($items, $by, $order);
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
    function drewlabs_core_array_find_index_by(array $items, $by, $search, $start = null, $end = null): ?int
    {
        return Arr::findIndexBy($items, $by, $search, $start, $end);
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
        return Arr::combine($lvalue, $rvalue);
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
        return Arr::search($needle, $items, $strict_mode);
    }
}

if (!function_exists('drewlabs_core_array_where')) {
    /**
     * Filter the array using the given callback.
     *
     * @param array $array
     * @param bool  $preserveKeys
     *
     * @return array
     */
    function drewlabs_core_array_where($array, callable $callback, $preserveKeys = true)
    {
        return Arr::where($array, $callback, $preserveKeys);
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
        return  Arr::keyExists($array, $key);
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
        return Arr::isArrayable($value);
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
        return Arr::fromObject($value);
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
        return Arr::has($array, $keys);
    }
}

if (!function_exists('drewlabs_core_array_get')) {
    /**
     * Get an item from an array using "dot" notation.
     *
     * @param \ArrayAccess|array $array
     * @param string|\Closure    $key
     * @param mixed              $default
     *
     * @return mixed
     */
    function drewlabs_core_array_get($array, $key, $default = null)
    {
        return Arr::get($array, $key, $default);
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
        return Arr::set($array, $key, $value);
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
        return Arr::map($items, $callback);
    }
}

if (!function_exists('drewlabs_core_array_iter')) {
    /**
     * Loop through a traversable and apply a given callback on each item and return an iterator.
     *
     * @param \Traversable|array $items
     *
     * @return \Traversable
     */
    function drewlabs_core_array_iter($items, callable $callback)
    {
        return Arr::iter($items, $callback);
    }
}

if (!function_exists('drewlabs_core_array_contains_all')) {

    /**
     * Checks if a source array contains all the elements of another array.
     * 
     * @param array $array 
     * @param array $sub 
     * @return bool 
     */
    function drewlabs_core_array_contains_all(array $array, array $sub)
    {
        return Arr::containsAll($array, $sub);
    }
}

if (!function_exists('is_assoc')) {

    /**
     * Checks if an array is an associative array.
     * 
     * @deprecated 1.0.0
     * @param array $value 
     * @return bool 
     */
    function is_assoc(array $value)
    {
        return Arr::isassoc($value);
    }
}

if (!function_exists('drewlabs_core_array_is_assoc')) {
    /**
     * Checks if an array is an associative array.
     * 
     * @param array $value 
     * @return bool 
     */
    function drewlabs_core_array_is_assoc(array $value)
    {
        return Arr::isassoc($value);
    }
}

if (!function_exists('drewlabs_core_array_is_full_assoc')) {
    /**
     * Checks if an array is an associative array.
     *
     * This checks if all array keys are string values
     *
     * Performs a O(n) comparison in a worst case scenario
     *
     * Use it instead of {drewlabs_core_array_is_assoc} to increase
     * error checking on full associative arrays
     * 
     * @param array $value 
     * @return bool 
     */
    function drewlabs_core_array_is_full_assoc(array $value)
    {
        return Arr::isallassoc($value);
    }
}

if (!function_exists('drewlabs_core_array_group_count')) {

    /**
     * Group array values by the number of their occurence in the array.
     * 
     * @param array $array 
     * @return array 
     */
    function drewlabs_core_array_group_count(array $array)
    {
        return Arr::groupCount($array);
    }
}

if (!function_exists('drewlabs_core_array_except')) {
    /**
     * Return all items in an array execpt the specified keys.
     * 
     * @param array $array 
     * @param mixed $keys 
     * @return array 
     */
    function drewlabs_core_array_except(array $array, $keys)
    {
        return Arr::except($array, $keys);
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
        return Arr::remove($array, $keys);
    }
}

if (!function_exists('drewlabs_core_array_udt_to_array')) {
    /**
     * Convert a given user define type to array.
     *
     * If $preserve_keys = false the first layer of the array is
     *
     * @param mixed $value
     * @param bool  $preserveKeys
     *
     * @return array|null
     */
    function drewlabs_core_array_udt_to_array($value, $preserveKeys = true)
    {
        return Arr::udtToArray($value, $preserveKeys);
    }
}

if (!function_exists('drewlabs_core_array_zip')) {
    /**
     * Zip two or more array values in a single list.
     *
     * @param array|mixed $lhs
     * @param array|mixed ...$rhs
     *
     * @return array[]
     */
    function drewlabs_core_array_zip($lhs, ...$rhs)
    {
        return Arr::zip($lhs, ...$rhs);
    }
}

if (!function_exists('drewlabs_core_array_szip')) {
    /**
     * Zip two or more array values in a single list.
     *
     * Arrays passed as paramter must of of the same size as the first array
     *
     * @param array|mixed $lhs
     * @param array|mixed ...$rhs
     *
     * @return array[]
     */
    function drewlabs_core_array_szip($lhs, ...$rhs)
    {
        return Arr::szip($lhs, ...$rhs);
    }
}

if (!function_exists('drewlabs_core_array_is_no_assoc_array_list')) {
    /**
     * Process entries in the provided list and return true if the list is a list of list.
     *
     * @return bool
     */
    function drewlabs_core_array_is_no_assoc_array_list(array $items)
    {
        return Arr::isnotassoclist($items);
    }
}

if (!function_exists('drewlabs_core_array_value_retriever_func')) {
    /**
     * Get a value retrieving callback.
     *
     * @param string|null $value
     *
     * @return callable
     */
    function drewlabs_core_array_value_retriever_func($value = null)
    {
        return Arr::valueRetriever($value);
    }
}

if (!function_exists('drewlabs_core_array_unique')) {
    /**
     * Returns the unique values in the list
     *
     * @param array  $haystack
     * @param string $key
     * @param bool   $strict
     *
     * @return array
     */
    function drewlabs_core_array_unique($haystack, ?string $key = null, $strict = false)
    {
        return Arr::unique($haystack, $key, $strict);
    }
}
if (!function_exists('drewlabs_core_array_key_last')) {
    /**
     * Return the last key of a php array.
     *
     * @return int|string|mixed
     */
    function drewlabs_core_array_key_last(array $list)
    {
        return Arr::keyLast($list);
    }
}

if (!function_exists('drewlabs_core_array_key_first')) {
    /**
     * Returns the first key of a PHP array.
     *
     * @return int|string|mixed
     */
    function drewlabs_core_array_key_first(array $list)
    {
        return Arr::keyFirst($list);
    }
}

if (!function_exists('drewlabs_core_array_last')) {
    /**
     * Return the last element of a php array.
     *
     * @return int|string|mixed
     */
    function drewlabs_core_array_last(array $list)
    {
        return Arr::last($list);
    }
}

if (!function_exists('drewlabs_core_array_first')) {
    /**
     * Returns the first element of a PHP array.
     *
     * @return int|string|mixed
     */
    function drewlabs_core_array_first(array $list)
    {
        return Arr::first($list);
    }
}

if (!function_exists('drewlabs_core_array_bsearch')) {
    /**
     * Perform a binary search while providing a closure as predicate that provide the compison expression
     * If no closure is provided it use === sign to compare values.
     *
     * Return BinarySearchResult::FOUND, BinarySearchResult::LEFT or BinarySearchResult::RIGHT to indicate
     * whether to search in in the lower or upper bound
     *
     * @param mixed    $needle
     * @param int|null $l      First item key
     * @param int|null $r      Last item key
     *
     * @return int
     */
    function drewlabs_core_array_bsearch(
        array $list,
        $needle = null,
        ?Closure $fn = null,
        ?int $l = null,
        ?int $r = null
    ) {
        return Arr::bsearch($list, $needle, $fn, $l, $r);
    }
}

if (!function_exists('drewlabs_core_array_ssearch')) {
    /**
     * Perform a sequential search on array and apply a predicate function to it if one is passed.
     *
     * @param mixed    $x
     * @param \Closure $fn
     *
     * @return int
     */
    function drewlabs_core_array_ssearch(
        array $list,
        $x = null,
        ?Closure $fn = null
    ) {
        return Arr::ssearch($list, $x, $fn);
    }
}

if (!function_exists('drewlabs_core_array_only')) {

    /**
     * Filter array returning only the values matching the provided keys.
     *
     * @param array $keys
     *
     * @throws InvalidArgumentException
     *
     * @return array
     */
    function drewlabs_core_array_only(array $list, $keys = [], bool $use_keys = true)
    {
        return Arr::only($list, $keys, $use_keys);
    }
}

if (!function_exists('drewlabs_core_array_wrap')) {

    /**
     * Wrap the value to an array if not an array, or return it if it is an array.
     *
     * @param array|mixed $value
     *
     * @return array
     */
    function drewlabs_core_array_wrap($value)
    {
        return Arr::wrap($value);
    }
}

if (!function_exists('drewlabs_core_array_exists')) {
    /**
     * Enhanced implementation of the array_key_exists function.
     *
     * @param array|\ArrayAccess|mixed $array
     * @param mixed                    $key
     *
     * @return mixed
     */
    function drewlabs_core_array_exists($array, $key)
    {
        return Arr::exists($array, $key);
    }
}

if (!function_exists('drewlabs_core_array_shuffle')) {
    /**
     * Shuffle the list of element in an array.
     *
     * @param int $seed
     *
     * @return array
     */
    function drewlabs_core_array_shuffle(array $list, ?int $seed = null)
    {

        return Arr::shuffle($list, $seed);
    }
}
