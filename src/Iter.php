<?php

namespace Drewlabs\Core\Helpers;

use Closure;
use InvalidArgumentException;
use Iterator;
use Traversable;

class Iter
{
    /**
     * Map through the values of a given iterator.
     * 
     * @param Iterator $iterator 
     * @param Closure $callback 
     * @param bool $preserveKeys 
     * @return Traversable 
     */
    public static function map(
        Iterator $iterator,
        Closure $callback,
        $preserveKeys = true
    ) {
        foreach ($iterator as $key => $value) {
            if ($preserveKeys) {
                yield $key => $callback($value, $key);
            } else {
                yield $callback($value, $key);
            }
        }
    }

    /**
     * Apply a filter to the values of a given iterator.
     *
     * @param Iterator $iterator
     * @param Closure $predicate
     * @param boolean $preserveKeys
     * @param int  $flags         // Indicates whether to use keys or
     *                            both $key and value in the filter function
     * @return Traversable
     */
    public static function filter(
        Iterator $iterator,
        Closure $predicate,
        $preserveKeys = true,
        $flags = \ARRAY_FILTER_USE_BOTH
    ) {
        foreach ($iterator as $key => $value) {
            if (!(\ARRAY_FILTER_USE_BOTH === $flags ? $predicate($value, $key) : $predicate($key))) {
                continue;
            }
            if ($preserveKeys) {
                yield $key => $value;
            } else {
                yield $value;
            }
        }
    }

    /**
     * Apply a reducer to the values of a given iterator.
     * 
     * @param Iterator $iterator 
     * @param Closure $reducer 
     * @param mixed $initial 
     * @return mixed 
     */
    public static function reduce(
        Iterator $iterator,
        Closure $reducer,
        $initial = null
    ) {
        $output = $initial;
        iterator_apply(
            $iterator,
            static function (Iterator $iterator) use ($reducer, &$output) {
                [$current, $key] = [$iterator->current(), $iterator->key()];
                $output = $reducer($output, $current, $key);
                return true;
            },
            [$iterator]
        );
        return $output;
    }

    /**
     * Filter iterator returning only the values matching the provided keys.
     * 
     * @param Iterator $iterator 
     * @param array|string|Traversable $keys 
     * @param bool $useKeys 
     * @return Traversable 
     * @throws InvalidArgumentException 
     */
    public static function only(
        Iterator $iterator,
        $keys = [],
        bool $useKeys = true
    ) {
        if (!is_string($keys) && !is_array($keys) && !($keys instanceof Iterator)) {
            throw new InvalidArgumentException('$keys parameter must be a PHP string|array or a validate iterator');
        }
        $keys = is_string($keys) ? [$keys] : (is_array($keys) ? $keys : iterator_to_array($keys));

        return self::filter(
            $iterator,
            static function ($current) use ($keys) {
                return in_array($current, $keys, true);
            },
            true,
            $useKeys ? \ARRAY_FILTER_USE_KEY : \ARRAY_FILTER_USE_BOTH
        );
    }

    /**
     * Returns a traversable of values not matching user provided values.
     * 
     * @param Iterator $iterator 
     * @param array|string|Traversable $values 
     * @param bool $useKeys 
     * @return Traversable 
     * @throws InvalidArgumentException 
     */
    public static function except(
        Iterator $iterator,
        $values = [],
        bool $useKeys = true
    ) {
        if (!is_string($values) && !is_array($values) && !($values instanceof Iterator)) {
            throw new InvalidArgumentException('$keys parameter must be a PHP string|array or a validate iterator');
        }
        $values = is_string($values) ? [$values] : (is_array($values) ? $values : iterator_to_array($values));

        return self::filter(
            $iterator,
            static function ($current) use ($values) {
                return !in_array($current, $values, true);
            },
            true,
            $useKeys ? \ARRAY_FILTER_USE_KEY : \ARRAY_FILTER_USE_BOTH
        );
    }

    /**
     * Collapse an array of arrays into a single array.
     *
     * @param array|Traversable $value
     *
     * @return array
     */
    public static function collapse($value)
    {
        $results = [];
        foreach ($value as $value) {
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
