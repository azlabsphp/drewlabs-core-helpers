<?php

declare(strict_types=1);

use Drewlabs\Core\Helpers\Str;

/*
 * This file is part of the Drewlabs package.
 *
 * (c) Sidoine Azandrew <azandrewdevelopper@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

if (!function_exists('drewlabs_core_strings_starts_with')) {
    /**
     * Check if a given string starts with a substring.
     *
     * @param string $haystack
     * @param string $needle
     *
     * @return bool
     */
    function drewlabs_core_strings_starts_with($haystack, $needle)
    {
        return Str::startsWith($haystack, $needle);
    }
}

if (!function_exists('drewlabs_core_strings_ends_with')) {
    /**
     * Check if a given string ends with a substring.
     *
     * @param string $haystack
     * @param string $needle
     *
     * @return bool
     */
    function drewlabs_core_strings_ends_with($haystack, $needle)
    {
        return Str::endsWith($haystack, $needle);
    }
}
if (!function_exists('drewlabs_core_strings_sanitize')) {

    /**
     * Removes some characters from the string.
     *
     * @param string $search
     * @param string $haystack
     * @param string $replacement
     *
     * @return string|string[]
     */
    function drewlabs_core_strings_sanitize($search, $haystack, $replacement = '')
    {
        return Str::sanitize($search, $haystack, $replacement);
    }
}

if (!function_exists('drewlabs_core_strings_is_str')) {
    /**
     * Check if a given variable is a string.
     *
     * @param string $value
     *
     * @return bool
     */
    function drewlabs_core_strings_is_str($value)
    {
        return Str::isStr($value);
    }
}

if (!function_exists('drewlabs_core_strings_to_lower_case')) {
    /**
     * Converts string to lowercase.
     *
     * @param string $value
     *
     * @return string
     */
    function drewlabs_core_strings_to_lower_case(string $value)
    {
        return Str::lower($value);
    }
}

if (!function_exists('drewlabs_core_strings_to_upper_case')) {
    /**
     * Converts string to uppercase.
     *
     * @param string $value
     *
     * @return string
     */
    function drewlabs_core_strings_to_upper_case($value)
    {
        return Str::upper($value);
    }
}

if (!function_exists('drewlabs_core_strings_capitalize')) {
    /**
     * Converts first character of a string to uppercase.
     *
     * @param string $value
     *
     * @return string
     */
    function drewlabs_core_strings_capitalize($value)
    {
        return Str::capitalize($value);
    }
}

if (!function_exists('drewlabs_core_strings_contains')) {
    /**
     * Determine if a given string contains a given substring.
     *
     * @param string       $haystack
     * @param string|array $needle
     *
     * @return bool
     */
    function drewlabs_core_strings_contains($haystack, $needle)
    {
        return Str::contains($haystack, $needle);
    }
}

if (!function_exists('drewlabs_core_strings_is_same')) {
    /**
     * Checks if two strings are same.
     *
     * @param string $lhs
     * @param string $rhs
     *
     * @return bool
     */
    function drewlabs_core_strings_is_same($lhs, $rhs)
    {
        return Str::same($lhs, $rhs);
    }
}

if (!function_exists('drewlabs_core_strings_concat')) {
    /**
     * Glue together other function parameters with the first {$separator} parameter.
     *
     * @param string      $separator
     * @param array|mixed ...$values
     *
     * @return string
     */
    function drewlabs_core_strings_concat($separator, ...$values)
    {
        return Str::concat($separator, ...$values);
    }
}

if (!function_exists('drewlabs_core_strings_from_array')) {
    /**
     * Glue together items in the provided array using the provided seperator.
     *
     * @return string
     */
    function drewlabs_core_strings_from_array(array $values, $delimiter = ',')
    {
        return Str::join($values, $delimiter);
    }
}

if (!function_exists('drewlabs_core_strings_to_array')) {
    /**
     * Explode a string variable into an array.
     *
     * @param string $delimiter
     *
     * @return array
     */
    function drewlabs_core_strings_to_array(string $value, $delimiter = ',')
    {
        return Str::split($value, $delimiter);
    }
}

if (!function_exists('drewlabs_core_strings_rtrim')) {
    /**
     * Strip the $char character from the end of the $str string.
     *
     * @param string      $str
     * @param string|null $char
     *
     * @return string
     */
    function drewlabs_core_strings_rtrim($str, $char = null)
    {
        return Str::rtrim($str, $char);
    }
}

if (!function_exists('drewlabs_core_strings_ltrim')) {
    /**
     * Strip the $char character from the begin of the $str string.
     *
     * @param string      $str
     * @param string|null $char
     *
     * @return string
     */
    function drewlabs_core_strings_ltrim($str, $char = null)
    {
        return Str::ltrim($str, $char);
    }
}

if (!function_exists('drewlabs_core_strings_rand_md5')) {
    /**
     * Generate a random string using PHP md5() uniqid() and microtime() functions.
     */
    function drewlabs_core_strings_rand_md5()
    {
        return Str::md5();
    }
}

if (!function_exists('drewlabs_core_strings_replace')) {
    /**
     * Replace provided subjects in the provided string.
     *
     * @param string          $search
     * @param string          $replacement
     * @param string|string[] $subject
     * @param int             $count
     *
     * @return string|string[]
     */
    function drewlabs_core_strings_replace($search, $replacement, $subject, $count = null)
    {
        return Str::replace($search, $replacement, $subject, $count);
    }
}

if (!function_exists('drewlabs_core_strings_after')) {
    /**
     * Returns the string after the first occurence of the provided character.
     *
     * @param string $character
     * @param string $haystack
     *
     * @return string
     */
    function drewlabs_core_strings_after($character, $haystack)
    {
        return Str::after($character, $haystack);
    }
}

if (!function_exists('drewlabs_core_strings_after_last')) {
    /**
     * Returns the string after the last occurence of the provided character.
     *
     * @param string $character
     * @param string $haystack
     *
     * @return string
     */
    function drewlabs_core_strings_after_last($character, $haystack)
    {
        return Str::afterLast($character, $haystack);
    }
}

if (!function_exists('drewlabs_core_strings_before')) {
    /**
     * Returns the string before the first occurence of the provided character.
     *
     * @param string $character
     * @param string $haystack
     *
     * @return string
     */
    function drewlabs_core_strings_before($character, $haystack)
    {
        return Str::before($character, $haystack);
    }
}

if (!function_exists('drewlabs_core_strings_before_last')) {
    /**
     * Returns the string before the last occurence of the provided character.
     *
     * @param string $character
     * @param string $haystack
     *
     * @return string
     */
    function drewlabs_core_strings_before_last($character, $haystack)
    {
        return Str::beforeLast($character, $haystack);
    }
}

if (!function_exists('drewlabs_core_strings_between')) {
    /**
     * Returns the string between the first occurence of both provided characters.
     *
     * @param string $character
     * @param string $that
     * @param string $haystack
     *
     * @return string
     */
    function drewlabs_core_strings_between($character, $that, $haystack)
    {
        return Str::between($character, $that, $haystack);
    }
}

if (!function_exists('drewlabs_core_strings_between_last')) {
    /**
     * Returns the string between the last occurence of both provided characters.
     *
     * @param string $character
     * @param string $that
     * @param string $haystack
     *
     * @return string
     */
    function drewlabs_core_strings_between_last($character, $that, $haystack)
    {
        return Str::betweenLast($character, $that, $haystack);
    }
}

if (!function_exists('drewlabs_core_strings_strrevpos')) {
    /**
     * Return the provided string in the reverse order.
     *
     * @param string $haystack
     * @param string $needle
     *
     * @return int|null
     */
    function drewlabs_core_strings_strrevpos($haystack, $needle)
    {
        return Str::strrevpos($haystack, $needle);
    }
}

if (!function_exists('drewlabs_core_strings_parse_tpl')) {

    /**
     * Simple template parsing function for replacing properties|keys of an associative
     * array by their corresponding values.
     *
     * Usage:
     * ```
     * $data = array(
     *           "param1" => "Hello",
     *           "param2" => "World",
     *  );
     *  $parsed_str = parse_tpl_str('This is a program just for saying {{$param1}}, to the {{ $param2 }} !!!', $data)
     * ```
     *
     * @param string       $str
     * @param array|object $data
     *
     * @return string
     */
    function drewlabs_core_strings_parse_tpl($str, $data)
    {
        return Str::parse($str, $data);
    }
}
if (!function_exists('drewlabs_core_strings_value_or_nullable')) {

    /**
     * Convert an enpty striing into PHP Nullable type.
     *
     * @return string|null
     */
    function drewlabs_core_strings_value_or_nullable($value)
    {
        return Str::valueOrNull($value);
    }
}

if (!function_exists('drewlabs_core_strings_as_camel_case')) {
    /**
     * Convert provided word into camel case.
     * If $capitalize_first_chr === true, It capitalize the first character of the
     * generated string as well.
     *
     * @param bool   $capitalize_first_chr
     * @param string $delimiter
     *
     * @return string
     */
    function drewlabs_core_strings_as_camel_case(string $str, $capitalize_first_chr = true, $delimiter = '_')
    {
        return Str::camelize($str, $capitalize_first_chr, $delimiter);
    }
}

if (!function_exists('drewlabs_core_strings_as_camel_case_regex')) {
    /**
     * Convert provided word into camel case.
     * If $capitalize_first_chr === true, It capitalize the first character of the
     * generated string as well.
     *
     * @param bool   $capitalize_first_chr
     * @param string $delimiter
     *
     * @return string
     */
    function drewlabs_core_strings_as_camel_case_regex(string $str, $capitalize_first_chr = true, $delimiter = '{[_]+}')
    {
        return Str::regexCamelize($str, $capitalize_first_chr, $delimiter);
    }
}

if (!function_exists('drewlabs_core_strings_as_snake_case')) {
    /**
     * Convert provided string into snake case.
     *
     * @param string $delimiter
     *
     * @return string
     */
    function drewlabs_core_strings_as_snake_case(string $str, $delimiter = '_', $delimiter_escape_char = '\\')
    {
        return Str::snakeCase($str, $delimiter, $delimiter_escape_char);
    }
}

if (!function_exists('drewlabs_core_strings_as_snake_case_regex')) {
    /**
     * Convert provided string into snake case using regular expression.
     *
     * @param string $delimiter
     *
     * @return string
     */
    function drewlabs_core_strings_as_snake_case_regex(string $str, $delimiter = '{[_]+}')
    {
        return Str::regexSnakeCase($str, $delimiter);
    }
}

if (!function_exists('drewlabs_core_strings_is_upper')) {

    /**
     * Checks if a given character is upper case or lowercase.
     * 
     * @param mixed $chr 
     * @return bool 
     */
    function drewlabs_core_strings_is_upper($chr)
    {
        return Str::isUpper($chr);
    }
}

if (!function_exists('drewlabs_core_strings_cadena_rtf')) {
    /**
     * String to cadena rtf.
     *
     * @return string
     */
    function drewlabs_core_strings_cadena_rtf(string $haystack)
    {
        return Str::cadenartf($haystack);
    }
}

if (!function_exists('drewlabs_core_strings_ordutf8')) {
    /**
     * String to UTF-8 encoded.
     *
     * @param int $offset
     *
     * @return string
     */
    function drewlabs_core_strings_ordutf8(string $string, &$offset)
    {
        return Str::ordutf8($string, $offset);
    }
}
