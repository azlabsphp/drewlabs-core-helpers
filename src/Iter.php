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

use Iterator;
use Traversable;

final class Iter
{
    /**
     * Map through the values of a given iterator.
     *
     * Note: The function returns an array iterator.
     *
     * @param bool $preserveKeys
     *
     * @return \Iterator
     */
    public static function map(
        \Iterator $iterator,
        callable $callback,
        $preserveKeys = true
    ) {
        return (static function () use ($iterator, $preserveKeys, &$callback) {
            foreach ($iterator as $key => $value) {
                if ($preserveKeys) {
                    yield $key => \call_user_func($callback, $value, $key);
                } else {
                    yield \call_user_func($callback, $value, $key);
                }
            }
        })();
    }

    /**
     * Apply a filter to the values of a given iterator.
     *
     * Note: The function returns an array iterator.
     *
     * @param bool $preserveKeys
     * @param int  $flags        // Indicates whether to use keys or
     *                           both $key and value in the filter function
     *
     * @return \Iterator
     */
    public static function filter(
        \Iterator $iterator,
        callable $predicate,
        $preserveKeys = true,
        $flags = \ARRAY_FILTER_USE_BOTH
    ) {
        return (static function () use (
            $iterator,
            $preserveKeys,
            &$predicate,
            $flags
        ) {
            foreach ($iterator as $key => $value) {
                if (!(\ARRAY_FILTER_USE_BOTH === $flags ?
                    \call_user_func($predicate, $value, $key) :
                    \call_user_func($predicate, $key))) {
                    continue;
                }
                if ($preserveKeys) {
                    yield $key => $value;
                } else {
                    yield $value;
                }
            }
        })();
    }

    /**
     * Apply a reducer to the values of a given iterator.
     *
     * @param mixed $initial
     *
     * @return mixed
     */
    public static function reduce(
        \Iterator $iterator,
        callable $reducer,
        $initial = null
    ) {
        $output = $initial;
        iterator_apply(
            $iterator,
            static function (\Iterator $iterator) use ($reducer, &$output) {
                [$current, $key] = [$iterator->current(), $iterator->key()];
                $output = \call_user_func($reducer, $output, $current, $key);

                return true;
            },
            [$iterator]
        );

        return $output;
    }

    /**
     * Filter iterator returning only the values matching the provided keys.
     *
     * @param array|string|\Traversable $keys
     *
     * @throws \InvalidArgumentException
     *
     * @return \Traversable
     */
    public static function only(
        \Iterator $iterator,
        $keys = [],
        bool $useKeys = true
    ) {
        if (
            !\is_string($keys)
            && !\is_array($keys)
            && !($keys instanceof \Iterator)
        ) {
            throw new \InvalidArgumentException('$keys parameter must be a PHP string|array or a validate iterator');
        }
        $keys = \is_string($keys) ?
            [$keys] : (\is_array($keys) ?
                $keys :
                iterator_to_array($keys));

        return static::filter(
            $iterator,
            static function ($current) use ($keys) {
                return \in_array($current, $keys, true);
            },
            true,
            $useKeys ? \ARRAY_FILTER_USE_KEY : \ARRAY_FILTER_USE_BOTH
        );
    }

    /**
     * Returns a traversable of values not matching user provided values.
     *
     * @param array|string|\Traversable $values
     *
     * @throws \InvalidArgumentException
     *
     * @return \Traversable
     */
    public static function except(
        \Iterator $iterator,
        $values = [],
        bool $useKeys = true
    ) {
        if (
            !\is_string($values)
            && !\is_array($values)
            && !($values instanceof \Iterator)
        ) {
            throw new \InvalidArgumentException('$keys parameter must be a PHP string|array or a validate iterator');
        }
        $values = \is_string($values) ?
            [$values] : (\is_array($values) ?
                $values :
                iterator_to_array($values));

        return static::filter(
            $iterator,
            static function ($current) use ($values) {
                return !\in_array($current, $values, true);
            },
            true,
            $useKeys ? \ARRAY_FILTER_USE_KEY : \ARRAY_FILTER_USE_BOTH
        );
    }

    /**
     * Collapse an array of arrays into a single array.
     *
     * @param array|\Traversable $value
     *
     * @return array
     */
    public static function collapse($value)
    {
        $results = [];
        foreach ($value as $value) {
            if (\is_object($value) && method_exists($value, 'all')) {
                $value = $value->all();
            } elseif (!\is_array($value)) {
                continue;
            }
            $results[] = $value;
        }

        return array_merge([], ...$results);
    }

    /**
     * Creates a Traversable of values in between $start and $limit.
     *
     * @throws \LogicException
     *
     * @return \Generator<int, int, mixed, void>
     */
    public static function range(int $start, int $limit, int $step = 1)
    {
        if ((($start <= $limit) && ($step <= 0)) || (!($start <= $limit) && $step >= 0)) {
            throw new \LogicException('Step must be positive');
        }
        if ($start <= $limit) {
            for ($index = $start; $index <= $limit; $index += $step) {
                yield $index;
            }
        } else {
            for ($index = $start; $index >= $limit; $index += $step) {
                yield $index;
            }
        }
    }
}
