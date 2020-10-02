<?php

define('DREWLABS_CORE_ORD_ASC', 'ASC');
define('DREWLABS_CORE_ORD_DESC', 'DESC');


if (!function_exists('drewlabs_core_compare_numeric')) {
    /**
     * Compare two variable of numeric type
     *
     * @param int|float|double $a
     * @param int|float|double $b
     *
     * @return int
     */
    function drewlabs_core_compare_numeric($a, $b, $order)
    {
        return ($order === DREWLABS_CORE_ORD_DESC) ? ($a - $b >= 0 ? 1 : -1) : ($a - $b >= 0 ? -1 : 1);
    }
}

if (!function_exists('drewlabs_core_compare_str')) {
    /**
     * Compare two variable of numeric type
     *
     * @param int|float|double $a
     * @param int|float|double $b
     *
     * @return int
     */
    function drewlabs_core_compare_str($a, $b, $order)
    {
        return ($order === DREWLABS_CORE_ORD_DESC) ? ($a - $b >= 0 ? 1 : -1) : ($a - $b >= 0 ? -1 : 1);
    }
}

if (!function_exists('drewlabs_core_is_same')) {
    /**
     * Verify if two variables are same
     *
     * @param string $a
     * @param string $b
     *
     * @return bool
     */
    function drewlabs_core_is_same($a, $b, $strict = false)
    {
        return $strict ? $a == $b : $a === $b;
    }
}
