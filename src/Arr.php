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
use Drewlabs\Core\Helpers\Arrays\BinarySearchResult;
use Iterator;

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
    public static function sortBy(array &$items, $by, $order = DREWLABS_CORE_ORD_ASC)
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
            if (($a instanceof \ArrayAccess || \is_array($a)) && ($b instanceof \ArrayAccess || \is_array($b))) {
                $a = $a[$by];
                $b = $b[$by];
            }
            // Check if is stdClass type
            if (\is_object($a) && \is_object($b)) {
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
            $searched_item = \is_object($items[$mid]) ? $items[$mid]->{$by} : $items[$mid][$by];
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
            self::keyExists($array, $key) ||
            isset($array[$key])
        ) {
            return $array[$key];
        }

        if (false === strpos($key, '.')) {
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
     * @return \Iterator
     */
    public static function iter($items, callable $callback)
    {
        foreach ($items as $value) {
            // code...
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
    public static function isAssociative(array $value)
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
     * Use it instead of {Arr::isAssociative} to increase
     * error checking on full associative arrays
     *
     * @return bool
     */
    public static function isFullyAssociative(array $value)
    {
        if (null === $value) {
            return false;
        }

        return -1 === self::ssearch(array_keys($value), null, static function ($item) {
            return !\is_string($item);
        });
    }

    /**
     * Group array values by the number of their occurence in the array.
     *
     * @return array
     */
    public static function groupCount(array $array)
    {
        return array_reduce(array_values($array), static function ($carry, $current) {
            return array_merge(
                $carry,
                [
                    $current => \array_key_exists($current, $carry) ? $carry[$current] + 1 : 1,
                ]
            );
        }, []);
    }

    /**
     * Return all items in an array execpt the specified keys.
     *
     * @param string[]|string $keys
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
            if (self::isAssociative($array) && self::keyExists($array, $key)) {
                unset($array[$key]);
                continue;
            }
            if (!self::isAssociative($array) && ($key_ = array_search($key, $array, true))) {
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
     * Process entries in the provided list and return true if the list is a list of list.
     *
     * @return bool
     */
    public static function isNotAssociativeList(array $items)
    {
        // Check if the list is an associative list, and return false if it is
        if (0 !== \count(array_filter(array_keys($items), 'is_string'))) {
            return false;
        }

        return !empty($items) && array_filter($items, 'is_array') === $items;
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
     * @param array  $haystack
     * @param string $key
     * @param bool   $strict
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
        return !empty($list) ? \array_slice($list, -1, 1, false)[0] : null;
    }

    /**
     * Returns the first element of a PHP array.
     *
     * @return int|string|mixed
     */
    public static function first(array $list)
    {
        return !empty($list) ? \array_slice($list, 0, 1, false)[0] : null;
    }

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
    public static function bsearch(array $list, $needle = null, ?\Closure $fn = null, ?int $l = null, ?int $r = null)
    {
        $search = static function (
            array $array,
            $item = null,
            ?\Closure $predicate = null,
            ?int $start = null,
            ?int $end = null
        ) use (&$search) {
            $start = $start ?? (!empty($array) ? self::keyFirst($array) : 0);
            $end = $end ?? (!empty($array) ? self::keyLast($array) : 0);
            if ($end >= $start) {
                $mid = (int) (ceil($start + ($end - $start) / 2));
                $result = $predicate ? $predicate($array[$mid], $item) : null;
                // If the predicate return not null 0, match is found
                if ((null !== $result) ? (BinarySearchResult::FOUND === $result) : $array[$mid] === $item) {
                    return floor($mid);
                }
                // If the predicate return not null == 1, search the lower bound
                if ((null !== $result) ? BinarySearchResult::LEFT === $result : $array[$mid] > $item) {
                    return $search(
                        $array,
                        $item,
                        $predicate,
                        $start,
                        $mid - 1
                    );
                }

                // Else, search the upper bound
                return $search(
                    $array,
                    $item,
                    $predicate,
                    $mid + 1,
                    $end
                );
            }

            // We reach here when element
            // is not present in array
            return -1;
        };
        if (empty($list)) {
            return BinarySearchResult::LEFT;
        }
        // First convert array to numeric array to avoid dealing with associative array
        $list = array_values($list);

        return $search($list, $needle, $fn, $l, $r);
    }

    /**
     * Perform a sequential search on array and apply a predicate function to it if one is passed.
     *
     * @param mixed    $x
     * @param \Closure $fn
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
     * @param int $seed
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
}
