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
use Traversable;

class Arr
{
    /**
     * Sort a given array using the PHP built-in usort function.
     *
     * @param \Closure|callable $callback
     *
     * @return array
     */
    public static function sort(array &$items, $callback)
    {
        usort($items, $callback);

        return $items;
    }

    /**
     * Process entries in the provided list and return true if the list is a list of list.
     *
     * @return bool
     */
    public static function isList(array $items)
    {
        return array_filter($items, 'is_array') === $items;
    }

    /**
     * Sort the provided array in the user specified order.
     *
     * @param string $by
     * @param string $order
     */
    public static function sortBy(array &$items, $by, $order = 'asc')
    {
        $desc = 'desc' === strtolower($order) || (int) $order < 0;
        $str_compare = static function ($a, $b) use ($desc) {
            $result = strcmp($a, $b);

            return $desc ? $result >= 0 : $result < 0;
        };
        $num_compare = static function ($a, $b) use ($desc) {
            return $desc ? ($a - $b >= 0 ? 1 : -1) : ($a - $b >= 0 ? -1 : 1);
        };
        $compare = static function ($a, $b) use ($order, $by, $desc, &$str_compare, &$num_compare) {
            // Check first if is standard type in order to avoid error
            if (\is_string($a) || \is_string($b)) {
                return $str_compare($a, $b, $order);
            }
            if (is_numeric($a) || is_numeric($b)) {
                return $num_compare($a, $b);
            }
            // Check if is arrayable
            if (($a instanceof \ArrayAccess || \is_array($a)) && ($b instanceof \ArrayAccess || \is_array($b))) {
                $a = $a[$by];
                $b = $b[$by];
            }
            // Check if is stdClass type
            if (\is_object($a) && \is_object($b)) {
                $a = $a->{$by};
                $b = $b->{$by};
            }
            if (\is_string($a) || \is_string($b)) {
                return $str_compare($a, $b);
            }
            if (is_numeric($a) || is_numeric($b)) {
                return $num_compare($a, $b);
            }

            return $desc ? -1 : 1;
        };
        usort($items, $compare);

        return $items;
    }

    /**
     * Find index of an array element.
     *
     * @param string $by
     *
     * @return int|null
     */
    public static function findIndexBy(array $items, $by, $search, $start = null, $end = null): int
    {
        $low = $start ?? 0;
        $high = $end ?? \count($items) - 1;

        while ($low <= $high) {
            $mid = floor(($low + $high) / 2);
            $item = \is_object($items[$mid]) ? $items[$mid]->{$by} : $items[$mid][$by];
            if ($item === $search) {
                return $mid;
            }
            if ($search < $item) {
                $high = $mid - 1;
            } else {
                $low = $mid + 1;
            }
        }

        return -1;
    }

    /**
     * Combine values of two array into a single one.
     *
     * @return array
     */
    public static function combine(array $lvalue, array $rvalue)
    {
        return array_merge($lvalue, $rvalue);
    }

    /**
     * PHP search algorithm wrapper
     * It return the index of the matching elements.
     *
     * @param mixed $needle
     * @param bool  $strict_mode
     *
     * @return int|string|bool
     */
    public static function search($needle, array $items, $strict_mode = false)
    {
        return array_search($needle, $items, $strict_mode);
    }

    /**
     * Filter the array using the given callback.
     *
     * @param array $array
     * @param bool  $preserve_keys
     *
     * @return array
     */
    public static function where($array, callable $callback, $preserve_keys = true)
    {
        return $preserve_keys ? array_filter($array, $callback, \ARRAY_FILTER_USE_BOTH) : array_values(array_filter($array, $callback, \ARRAY_FILTER_USE_BOTH));
    }

    /**
     * Method for swapping two variables.
     *
     * @param mixed $lhs
     * @param mixed $rhs
     *
     * @return int
     */
    public static function swap(&$lhs, &$rhs)
    {
        [$lhs, $rhs] = [$rhs, $lhs];
    }

    /**
     * Determine if the given key exists in the provided array.
     *
     * @param \ArrayAccess|array $array
     * @param string|int         $key
     *
     * @return bool
     */
    public static function keyExists($array, $key)
    {
        if ($array instanceof \ArrayAccess) {
            return $array->offsetExists($key);
        }

        return \array_key_exists($key, $array);
    }

    /**
     * Determine whether the given value is array arrayable.
     *
     * @param mixed $value
     *
     * @return bool
     */
    public static function isArrayable($value)
    {
        return \is_array($value) || $value instanceof \ArrayAccess;
    }

    /**
     * Checks if the provided parameter is a PHP array variable.
     *
     * @param mixed $value
     *
     * @return bool
     */
    public static function isArray($value)
    {
        return \is_array($value);
    }

    /**
     * Convert a given object to array.
     *
     * @param mixed $value
     *
     * @return array|null
     */
    public static function fromObject($value)
    {
        $entryToArray = static function ($item) use (&$entryToArray) {
            if (self::isArrayable($item)) {
                return $item;
            }
            if (\is_object($item)) {
                $item = (array) $item;
                foreach ($item as $k => $v) {
                    if (\is_object($v)) {
                        $item[$k] = $entryToArray($v);
                    }
                }

                return $item;
            }

            return null;
        };

        return $entryToArray($value);
    }

    /**
     * Check if an array/object has a given property using "dot" notation or not.
     *
     * @param \ArrayAccess|array $array
     * @param string|array       $keys
     *
     * @return bool
     */
    public static function has($array, $keys)
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

            if (self::keyExists($array, $key)) {
                continue;
            }

            foreach (explode('.', (string) $key) as $segment) {
                if (self::isArrayable($sub_key) && self::keyExists($sub_key, $segment)) {
                    $sub_key = $sub_key[$segment];
                } else {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Get an item from an array using "dot" notation.
     *
     * @param \ArrayAccess|array $array
     * @param string|\Closure    $key
     * @param mixed              $default
     *
     * @return mixed
     */
    public static function get($array, $key, $default = null)
    {
        if (!self::isArrayable($array)) {
            return $default instanceof \Closure ? $default() : $default;
        }

        if (null === $key) {
            return $array;
        }

        if ($key instanceof \Closure) {
            return $key($array) ?? ($default instanceof \Closure ? $default() : $default);
        }

        if (
            self::keyExists($array, $key)
            || isset($array[$key])
        ) {
            return $array[$key];
        }

        if (!str_contains($key, '.')) {
            return $array[$key] ?? ($default instanceof \Closure ? $default() : $default);
        }

        foreach (explode('.', (string) $key) as $segment) {
            if (self::isArrayable($array) && self::keyExists($array, $segment)) {
                $array = $array[$segment];
            } else {
                return $default instanceof \Closure ? $default() : $default;
            }
        }

        return $array;
    }

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
    public static function set(&$array, $key, $value)
    {
        if (null === $key) {
            return $array = $value;
        }

        $keys = explode('.', (string) $key);

        while (\count($keys) > 1) {
            $key = array_shift($keys);

            // If the key doesn't exist at this depth, we will just create an empty array
            // to hold the next value, allowing us to create the arrays to hold final
            // values at the correct depth. Then we'll keep digging into the array.
            if (!isset($array[$key]) || !\is_array($array[$key])) {
                $array[$key] = [];
            }

            $array = &$array[$key];
        }

        $array[array_shift($keys)] = $value;

        return $array;
    }

    /**
     * Loop through items and return the result of the callback applied to them.
     *
     * @param array $items
     *
     * @return array
     */
    public static function map($items, callable $callback)
    {
        $keys = array_keys($items);
        $items = array_map($callback, $items, $keys);

        return array_combine($keys, $items);
    }

    /**
     * Loop through a traversable and apply a given callback on each item and return an iterator.
     *
     * @param \Traversable|array $items
     *
     * @return \Traversable
     */
    public static function iter($items, callable $callback)
    {
        foreach ($items as $value) {
            yield \call_user_func($callback, $value);
        }
    }

    /**
     * Checks if a source array contains all the elements of another array.
     *
     * @return bool
     */
    public static function containsAll(array $source, array $innerArray)
    {
        return \count(array_intersect($source, $innerArray)) === \count($innerArray);
    }

    /**
     * Checks if an array is an associative array.
     *
     * @return bool
     */
    public static function isassoc(array $value)
    {
        if (null === $value) {
            return false;
        }

        return array_keys($value) !== range(0, \count($value) - 1);
    }

    /**
     * Checks if an array is an associative array.
     *
     * This checks if all array keys are string values
     *
     * Performs a O(n) comparison in a worst case scenario
     *
     * @return bool
     */
    public static function isallassoc(array $value)
    {
        if (null === $value) {
            return false;
        }

        return -1 === self::ssearch(
            array_keys($value),
            null,
            static function ($item) {
                return !\is_string($item);
            }
        );
    }

    /**
     * Group array values by the number of their occurence in the array.
     *
     * @return array
     */
    public static function groupCount(array $array)
    {
        return array_reduce(
            array_values($array),
            static function ($carry, $current) {
                return array_merge(
                    $carry,
                    [
                        $current => \array_key_exists($current, $carry) ? $carry[$current] + 1 : 1,
                    ]
                );
            },
            []
        );
    }

    /**
     * Return all items in an array execpt the specified keys.
     *
     * @param mixed $keys
     *
     * @return array
     */
    public static function except(array $array, $keys)
    {
        self::remove($array, $keys);

        return $array;
    }

    /**
     * Remove a key or a list of keys from a given array.
     *
     * @param array           $array
     * @param string[]|string $keys
     *
     * @return void
     */
    public static function remove(&$array, $keys)
    {
        if (empty($array)) {
            return;
        }
        $original = &$array;
        $keys = (array) $keys;
        if (0 === \count($keys)) {
            return;
        }
        foreach ($keys as $key) {
            // if the exact key exists in the top-level, remove it
            if (self::isassoc($array) && self::keyExists($array, $key)) {
                unset($array[$key]);
                continue;
            }
            if (!self::isassoc($array) && ($key_ = array_search($key, $array, true))) {
                unset($array[$key_]);
                continue;
            }
            $parts = explode('.', (string) $key);
            // clean up before each pass
            $array = &$original;
            while (\count($parts) > 1) {
                $part = array_shift($parts);
                if (isset($array[$part]) && \is_array($array[$part])) {
                    $array = &$array[$part];
                } else {
                    continue;
                }
            }
            unset($array[array_shift($parts)]);
        }
    }

    /**
     * Convert a given user define type to array.
     *
     * If $preserve_keys = false the first layer of the array is
     *
     * @param mixed $value
     * @param bool  $preserve_keys
     *
     * @return array|null
     */
    public static function udtToArray($value, $preserve_keys = true)
    {
        if ($value instanceof \Traversable) {
            $value = iterator_to_array($value);
        } elseif ($value instanceof \JsonSerializable) {
            $value = (array) $value->jsonSerialize();
        } elseif (\is_object($value)) {
            if (method_exists($value, 'all')) {
                $value = $value->all();
            } elseif (method_exists($value, 'toArray')) {
                $value = $value->toArray();
            } elseif (method_exists($value, 'toJson')) {
                $value = json_decode($value->toJson(), true);
            } else {
                $value = self::fromObject($value) ?? [];
            }
        }
        if (!\is_array($value)) {
            throw new \InvalidArgumentException('Parameters must of of type array, an \stdClass, an object that define all(), toArray(), toJson() which return arrays, or are instance of'.\Traversable::class.', '.\JsonSerializable::class);
        }

        return $preserve_keys ? $value : array_values($value);
    }

    /**
     * Zip two or more array values in a single list.
     *
     * @param array|mixed $lhs
     * @param array|mixed ...$rhs
     *
     * @return array[]
     */
    public static function zip($lhs, ...$rhs)
    {
        $variadic_params = \array_slice(\func_get_args(), 1);
        $lhs = self::udtToArray($lhs, false);
        $arrayableItems = array_map(static function ($item) {
            return self::udtToArray($item, false);
        }, $variadic_params);
        $params = array_merge([static function () {
            return \func_get_args();
        }, $lhs], $arrayableItems);

        return array_map(...$params);
    }

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
    public static function szip($lhs, ...$rhs)
    {
        $variadic_params = \array_slice(\func_get_args(), 1);
        $lhs = self::udtToArray($lhs, false);
        $count = \count($lhs);
        // Transform all variadic params to array to ensure data integrity
        $arrayableItems = array_map(static function ($item) {
            return self::udtToArray($item, false);
        }, $variadic_params);
        // Ensure that all arrays are of the same size
        $all_same_size = array_filter($arrayableItems, static function ($v) use ($count) {
            return \count($v) === $count;
        }) === $arrayableItems;
        if (!$all_same_size) {
            throw new \InvalidArgumentException('All params must be of the same size');
        }

        return self::zip($lhs, ...$rhs);
    }

    /**
     * Returns true if the provided $items is a numerical indexed array
     * and each index value is an array.
     *
     * ```php
     * $result = Arr::isnotassoclist([
     *      [
     *          'hello', 'world'
     *      ],
     *      [
     *          'good', 'morning'
     *      ]
     * ]); // True
     * $result = Arr::isnotassoclist([
     *      'basic' => [
     *          'hello', 'world'
     *      ],
     *      'greetings' => [
     *          'good', 'morning'
     *      ]
     * ]); // False
     * ```
     *
     * @return bool
     */
    public static function isnotassoclist(array $items)
    {
        if (empty($items)) {
            return false;
        }

        return self::isList($items) && !self::isassoc($items);
    }

    /**
     * Checks if a given array is an associative array and each value of
     * the primary array is an array itself.
     *
     * ```php
     * <?php
     * $result = Arr::isassoclist([
     *  'h' => ['Hello'],
     *  'g' => ['Good Morning']
     * ]); // Returns true
     *
     * // While
     * $result  = Arr::isassoclist([
     *  'h' => ['Hello'],
     *  'g' => 'Good Moring'
     * ]); // returns false
     *
     * // And
     * $result = Arr::isassoclist([]); // Returns false
     * ```
     *
     * @return bool
     */
    public static function isassoclist(array $items)
    {
        if (empty($items)) {
            return false;
        }

        return (0 !== \count(array_filter(array_keys($items), 'is_string'))) && self::isList($items);
    }

    /**
     * Get a value retrieving callback.
     *
     * @param string|null $value
     *
     * @return callable
     */
    public static function valueRetriever($value = null)
    {
        return static function ($item) use ($value) {
            if (null === $value || !\is_string($value)) {
                return $item;
            }

            return self::get($item, $value);
        };
    }

    /**
     * Undocumented function.
     *
     * @param array $haystack
     * @param bool  $strict
     *
     * @return array
     */
    public static function unique($haystack, ?string $key = null, $strict = false)
    {
        $callback = self::valueRetriever($key);
        $exists = [];
        $out = [];
        foreach ($haystack as $key => $value) {
            // code...
            if (!\in_array($id = $callback($value), $exists, $strict)) {
                $out[$key] = $value;
            }
            $exists[] = $id;
        }

        return $out;
    }

    /**
     * Return the last key of a php array.
     *
     * @return int|string|mixed
     */
    public static function keyLast(array $list)
    {
        if (\function_exists('array_key_last')) {
            return array_key_last($list);
        }

        return !empty($list) ? key(\array_slice($list, -1, 1, true)) : null;
    }

    /**
     * Returns the first key of a PHP array.
     *
     * @return int|string|mixed
     */
    public static function keyFirst(array $list)
    {
        if (\function_exists('array_key_first')) {
            return array_key_first($list);
        }

        return key($list);
    }

    /**
     * Return the last element of a php array.
     *
     * @return int|string|mixed
     */
    public static function last(array $list)
    {
        return !empty($list) ? ($list[static::keyLast($list)] ?? null) : null;
    }

    /**
     * Returns the first element of a PHP array.
     *
     * @return int|string|mixed
     */
    public static function first(array $list)
    {
        return !empty($list) ? ($list[static::keyFirst($list)] ?? null) : null;
    }

    /**
     * Perform a binary search while providing a closure as predicate that provide the compison expression
     * If no closure is provided it use === sign to compare values.
     *
     * @param mixed $value
     *
     * @return int
     */
    public static function bsearch(array $haystack, $value = null, ?\Closure $predicate = null, ?int $start = null, ?int $end = null)
    {
        $start = $start ?? 0;
        $end = $end ?? (\count($haystack) - 1);
        $predicate = $predicate ?? static function ($source, $match) {
            if ($source === $match) {
                return 0;
            }
            if ($source > $match) {
                return -1;
            }

            return 1;
        };
        while ($start <= $end) {
            $mid = (int) ceil($start + ($end - $start) / 2);
            $result = $predicate($haystack[$mid], $value);
            if (0 === $result) {
                return $mid;
            }
            if (-1 === $result) {
                $end = $mid - 1;
            } else {
                $start = $mid + 1;
            }
        }

        return -1;
    }

    /**
     * Perform a sequential search on array and apply a predicate function to it if one is passed.
     *
     * @param mixed $x
     *
     * @return int
     */
    public static function ssearch(array $list, $x = null, ?\Closure $fn = null)
    {
        $index = -1;
        if (empty($list)) {
            return $index;
        }
        $it = new \ArrayIterator($list);
        while (($value = $it->current()) !== null) {
            if ($fn ? $fn($value, $x) : $value === $x) {
                $index = $it->key();
                break;
            }
            $it->next();
        }

        return $index;
    }

    /**
     * Filter array returning only the values matching the provided keys.
     *
     * @param array $keys
     *
     * @throws \InvalidArgumentException
     *
     * @return array
     */
    public static function only(array $list, $keys = [], bool $use_keys = true)
    {
        if (!\is_string($keys) && !\is_array($keys) && !($keys instanceof \Iterator)) {
            throw new \InvalidArgumentException('$keys parameter must be a PHP string|array or a validate iterator');
        }
        $keys = \is_string($keys) ? [$keys] : (\is_array($keys) ? $keys : iterator_to_array($keys));
        if (empty($keys)) {
            return [];
        }

        return array_filter($list, static function ($current) use ($keys) {
            return \in_array($current, $keys, true);
        }, $use_keys ? \ARRAY_FILTER_USE_KEY : \ARRAY_FILTER_USE_BOTH);
    }

    /**
     * Wrap the value to an array if not an array, or return it if it is an array.
     *
     * @param array|mixed $value
     *
     * @return array
     */
    public static function wrap($value)
    {
        if (null === $value) {
            return [];
        }

        return \is_array($value) ? $value : [$value];
    }

    /**
     * Enhanced implementation of the array_key_exists function.
     *
     * @param array|\ArrayAccess|mixed $array
     * @param mixed                    $key
     *
     * @return mixed
     */
    public static function exists($array, $key)
    {
        if (\is_object($array) && method_exists($array, 'has')) {
            return $array->has($key);
        }
        if ($array instanceof \ArrayAccess) {
            return $array->offsetExists($key);
        }

        return \array_key_exists($key, $array);
    }

    /**
     * Shuffle the list of element in an array.
     *
     * @return array
     */
    public static function shuffle(array $list, ?int $seed = null)
    {
        if (null === $seed) {
            shuffle($list);
        } else {
            mt_srand($seed);
            shuffle($list);
            mt_srand();
        }

        return $list;
    }

    /**
     * Removes all null values from an array.
     *
     * @return array
     */
    public static function filterNull(array $array)
    {
        return array_filter($array, static function ($value) {
            return null !== $value;
        });
    }

    /**
     * Apply filtering on an array removing values not matching the predicate.
     *
     * @param mixed $predicate
     *
     * @return array
     */
    public static function filter(array $array, ?callable $predicate = null, ?int $flag = 0)
    {
        if (null === $predicate) {
            return static::filterNull($array);
        }

        return array_filter($array, $predicate, $flag);
    }

    /**
     * Apply transformation function on filtered list.
     *
     * @return array
     */
    public static function filterMap(
        array $values,
        callable $transform,
        ?callable $predicate = null
    ) {
        return self::map(
            self::filter($values, $predicate),
            $transform
        );
    }

    /**
     * Create a PHP Array from  user provided value.
     *
     * @param \Traversable|array|mixed|null $values
     *
     * @return array
     */
    public static function create($values = null)
    {
        if (null === $values) {
            return [];
        }

        if ($values instanceof \Traversable) {
            return iterator_to_array($values);
        }

        if (\is_object($values) && method_exists($values, 'all')) {
            return $values->all();
        }

        if (\is_object($values) && method_exists($values, 'toArray')) {
            return $values->toArray();
        }

        return (array) $values;
    }

    /**
     * Get the values from a list data structure.
     *
     * @param \Traversable|array|mixed $values
     *
     * @return array
     */
    public static function values($values)
    {
        if ($values instanceof \Traversable) {
            return iterator_to_array($values, false);
        }

        if (\is_object($values) && method_exists($values, 'all')) {
            return array_values($values->all());
        }

        if (\is_object($values) && method_exists($values, 'toArray')) {
            return array_values($values->toArray());
        }
        throw new \InvalidArgumentException('Unsupported type provided as parameter!');
    }

    /**
     * @param int|string $index
     * @param bool       $preserverKeys
     *
     * @return array
     */
    public static function removeAt(array $array, $index, $preserverKeys = false)
    {
        if ($preserverKeys) {
            unset($array[$index]);

            return $array;
        }

        // Creates a new 0 based array removing item at a given index
        return array_merge(
            \array_slice($array, 0, $index),
            \array_slice($array, $index + 1)
        );
    }

    /**
     * Remove item from array item matched a specified predicate.
     *
     * ```php
     * <?php
     *
     * $array = [1, 2, 3, 4];
     *
     * $array = Arr::removeWhere($array, function() {
     * });
     * ```
     *
     * @param bool $preserverKeys
     *
     * @return array
     */
    public static function removeWhere(array $array, callable $predicate, $preserverKeys = false)
    {
        $index = -1;
        foreach ($array ?? [] as $key => $current) {
            ++$index;
            if ($predicate($current, $key)) {
                return self::removeAt($array, $preserverKeys ? $key : $index, $preserverKeys);
            }
        }

        return $array;
    }

    /**
     * Groups list by a given value.
     *
     * @param \Iterator|array $values
     * @param string|int      $key
     *
     * @return array
     */
    public static function groupBy(array $values, $key)
    {
        $key = (!\is_string($key) && \is_callable($key)) ? $key : static function ($value) use ($key) {
            if (\is_array($value)) {
                return $value[$key] ?? null;
            }
            if (\is_object($key)) {
                return $value->{$key};
            }

            return $value;
        };
        $results = [];
        foreach ($values as $key => $value) {
            $groupKeys = $key($value, $key);

            if (!\is_array($groupKeys)) {
                $groupKeys = [$groupKeys];
            }
            foreach ($groupKeys as $groupKey) {
                if (!\array_key_exists($groupKey, $results)) {
                    $results[$groupKey] = [];
                }
                $results[$groupKey][] = $value;
            }
        }

        return $results;
    }

    /**
     * @return array
     */
    public static function recursiveksort(array $value)
    {
        return static::_recursiveksort_($value, 'ksort');
    }

    /**
     * @return array
     */
    public static function recursivekrsort(array $value)
    {
        return static::_recursiveksort_($value, 'krsort');
    }

    /**
     * @param callable|\Closure $sortFunc
     *
     * @return array
     */
    private static function _recursiveksort_(array $value, $sortFunc)
    {
        if (null === $sortFunc) {
            $sortFunc = 'ksort';
        }
        // region Internal sort function
        $func = static function (array &$list) use ($sortFunc, &$func) {
            foreach ($list as $key => $value) {
                $is_object = \is_object($value);
                if ($is_object || \is_array($value)) {
                    $current = $is_object ? get_object_vars($value) : $value;
                    $func($current);
                    $list[$key] = $current;
                }
            }
            \call_user_func_array($sortFunc, [&$list]);
        };
        $func($value);

        // endregion Internal function
        return $value;
    }
}
