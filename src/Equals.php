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

class Equals
{
    /**
     * Compare two object using PHP === operator.
     *
     * @param mixed $a
     * @param mixed $b
     */
    public static function strict($a, $b): bool
    {
        return $a === $b;
    }

    /**
     * Shallow PHP object, primitive or array comparison function.
     *
     * ```php
     * <?php
     *
     * const $bool = shallow([], [1, 2, 3]); // false
     * ```
     *
     * @param mixed $a
     * @param mixed $b
     */
    public static function shallow($a, $b): bool
    {
        $type1 = \gettype($a);
        $type2 = \gettype($b);
        switch (true) {
            case $type1 !== $type2:
                return false;
            case 'boolean' === $type1 || 'integer' === $type1 || 'double' === $type1 || 'string' === $type1:
                return self::strict($a, $b);
            case 'array' === $type1:
                return self::arrayEquals($a, $b);
            case 'object' === $type1:
                return self::objectEquals($a, $b);
            case 'NULL' === $type1:
                return true;
            case 'resource' === $type1 || 'unknown type' === $type1:
                return true;
            default:
                return false; // Default use case may not be reach in most cases
        }
    }

    /**
     * Deep equality object comparator function for PHP language
     * It recursively apply strict comparison on object or array leaf
     * values.
     *
     * ```php
     * <?php
     *
     * const $bool = deepEqual([], [1, 2, 3]); // false
     *
     * const $bool = Functional::deepEqual([
     *      [1, 2, 3],
     *      [4, 5, 6]
     * ], [
     *      [1, 2, 3],
     *      [4, 5, 6]
     * ]); // true
     * ```
     *
     * @param mixed $a
     * @param mixed $b
     */
    public static function deep($a, $b): bool
    {
        $type1 = \gettype($a);
        $type2 = \gettype($b);
        switch (true) {
            case $type1 !== $type2:
                return false;
            case 'boolean' === $type1 || 'integer' === $type1 || 'double' === $type1 || 'string' === $type1:
                return self::strict($a, $b);
            case 'array' === $type1:
                return self::arrayDeepEqual($a, $b);
            case 'object' === $type1:
                return self::objectDeepEqual($a, $b);
            case 'NULL' === $type1:
                return true;
            case 'resource' === $type1 || 'unknown type' === $type1:
                return true;
            default:
                return true;
        }
    }

    /**
     * Compute the shallow equality of 2 PHP array objects.
     */
    public static function arrayEquals(array $a, array $b): bool
    {
        if ($a === $b) {
            return true;
        }
        if (\count($a) !== \count($b)) {
            return false;
        }
        // If keys are not equals return false
        $keysA = array_keys($a);
        $keysB = array_keys($b);
        foreach ($keysA as $k => $v) {
            if (!self::strict($keysA[$k], $keysB[$k]) || !self::strict($a[$v], $b[$v])) {
                return false;
            }
        }

        return true;
    }

    /**
     * Compute shallow equality of 2 PHP objects.
     */
    public static function objectEquals(object $a, object $b): bool
    {
        if ($a === $b) {
            return true;
        }
        if ($a !== $b) {
            return false;
        }
        $aReflector = new \ReflectionObject($a);
        $bReflector = new \ReflectionObject($b);
        $aProperties = $aReflector->getProperties(\ReflectionProperty::IS_PUBLIC);
        $bProperties = $bReflector->getProperties(\ReflectionProperty::IS_PUBLIC);
        if (!self::arrayEquals($aProperties, $bProperties)) {
            return false;
        }
        foreach ($aProperties as $key => $prop) {
            if (!self::strict($a->$prop, $b->$prop)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Method performs a deep equality on PHP object instances.
     *
     * It loops through all properties and deep properties of provided
     * object and check if they are equals
     *
     * @param mixed $a
     * @param mixed $b
     */
    public static function objectDeepEqual(object $a, object $b): bool
    {
        if ($a !== $b) {
            return false;
        }
        $aReflector = new \ReflectionObject($a);
        $bReflector = new \ReflectionObject($b);
        $aProperties = $aReflector->getProperties(\ReflectionProperty::IS_PUBLIC);
        $bProperties = $bReflector->getProperties(\ReflectionProperty::IS_PUBLIC);
        $bool = self::arrayDeepEqual($aProperties, $bProperties);
        if (false === $bool) {
            return false;
        }
        foreach ($aProperties as $key => $propName) {
            if (!self::deep($a->$propName, $b->$propName)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Compute deep equality of 2 PHP arrays/dictionaries.
     */
    public static function arrayDeepEqual(array $a, array $b): bool
    {
        $count = \count($a);
        // Require that they have the same size.
        if (\count($b) !== $count) {
            return false;
        }
        // Require that they have the same keys.
        $commonKeys = array_intersect_key($a, $b);
        if (\count($commonKeys) !== $count) {
            return false;
        }
        // Require that their keys be in the same order.
        $arrKeys1 = array_keys($a);
        $arrKeys2 = array_keys($b);
        foreach ($arrKeys1 as $key => $val) {
            if ($arrKeys1[$key] !== $arrKeys2[$key]) {
                return false;
            }
        }
        // They do have same keys and in same order.
        foreach ($a as $key => $val) {
            $bool = self::deep($a[$key], $b[$key]);
            if (false === $bool) {
                return false;
            }
        }

        return true;
    }
}
