<?php


if (!function_exists('drewlabs_core_strings_starts_with')) {
    /**
     * Check if a given string starts with a substring
     *
     * @param string $haystack
     * @param string $needle
     *
     * @return bool
     */
    function drewlabs_core_strings_starts_with($haystack, $needle)
    {
        return ($needle === "") || (substr($haystack, 0, strlen($needle)) === $needle);
    }
}

if (!function_exists('drewlabs_core_strings_ends_with')) {
    /**
     * Check if a given string ends with a substring
     *
     * @param string $haystack
     * @param string $needle
     *
     * @return bool
     */
    function drewlabs_core_strings_ends_with($haystack, $needle)
    {
        return ($needle === "") || (substr($haystack, -intval(strlen($needle))) === $needle);
    }
}
if (!function_exists('drewlabs_core_strings_sanitize')) {

    /**
     * Removes some characters from the string
     *
     * @param string $search
     * @param string $str_to_sanitize
     * @param string $replacement
     *
     * @return mixed
     */
    function drewlabs_core_strings_sanitize($search, $str_to_sanitize, $replacement = "")
    {
        return str_replace($search, $replacement, $str_to_sanitize);
    }
}

if (!function_exists('drewlabs_core_strings_is_str')) {
    /**
     * Check if a given variable is a string
     *
     * @param string $value
     *
     * @return bool
     */
    function drewlabs_core_strings_is_str($value)
    {
        return is_string($value);
    }
}

if (!function_exists('drewlabs_core_strings_to_lower_case')) {
    /**
     * Converts string to lowercase
     *
     * @param string $value
     *
     * @return string
     */
    function drewlabs_core_strings_to_lower_case($value)
    {
        return strtolower($value);
    }
}

if (!function_exists('drewlabs_core_strings_to_upper_case')) {
    /**
     * Converts string to uppercase
     *
     * @param string $value
     *
     * @return string
     */
    function drewlabs_core_strings_to_upper_case($value)
    {
        return strtoupper($value);
    }
}

if (!function_exists('drewlabs_core_strings_capitalize')) {
    /**
     * Converts first character of a string to uppercase
     *
     * @param string $value
     *
     * @return string
     */
    function drewlabs_core_strings_capitalize($value)
    {
        return ucfirst($value);
    }
}

if (!function_exists('drewlabs_core_strings_contains')) {
    /**
     * Determine if a given string contains a given substring.
     *
     * @param  string  $haystack
     * @param  string|array  $needle
     * @return bool
     */
    function drewlabs_core_strings_contains($haystack, $needle)
    {
        // Code patch for searching for string directly without converting it to an array of character
        if (\drewlabs_core_strings_is_str($needle)) {
            return ($needle !== '' && mb_strpos($haystack, $needle) !== false);
        }
        foreach ((array) $needle as $n) {
            if ($n !== '' && mb_strpos($haystack, $n) !== false) {
                return true;
            }
        }

        return false;
    }
}

if (!function_exists('drewlabs_core_strings_is_same')) {
    /**
     * Checks if two strings are same
     *
     * @param  string $lhs
     * @param  string $rhs
     * @return bool
     */
    function drewlabs_core_strings_is_same($lhs, $rhs)
    {
        return strcmp($lhs, $rhs) === 0 ? true : false;
    }
}

if (!function_exists('drewlabs_core_strings_concat')) {
    /**
     * Glue together other function parameters with the first {$separator} parameter
     *
     * @param string $separator
     * @param array|mixed ...$values
     * @return void
     */
    function drewlabs_core_strings_concat($separator, ...$values)
    {
        $entries = array_merge([], $values);
        return \drewlabs_core_strings_from_array($entries, $separator);
    }
}

if (!function_exists('drewlabs_core_strings_from_array')) {
    /**
     * Glue together items in the provided array using the provided seperator
     *
     * @param array $values
     * @param string $separator
     * @return void
     */
    function drewlabs_core_strings_from_array(array $values, $delimiter = ',')
    {
        if (!is_array($values)) {
            throw new \RuntimeException('Error parsing value... Provides an array value as parameter');
        }
        return implode($delimiter, $values);
    }
}

if (!function_exists('drewlabs_core_strings_to_array')) {
    /**
     * Explode a string variable into an array
     *
     * @param string $value
     * @param string $delimiter
     * @return array
     */
    function drewlabs_core_strings_to_array($value, $delimiter = ',')
    {
        if (!\drewlabs_core_strings_is_str($value)) {
            throw new \RuntimeException('Error parsing value... Provides a string value as parameter');
        }
        return $keys = explode($delimiter, $value);
    }
}

if (!function_exists('drewlabs_core_strings_rtrim')) {
    /**
     * Strip the $char character from the end of the $str string
     *
     * @param string $str
     * @param string|null $char
     * @return string
     */
    function drewlabs_core_strings_rtrim($str, $char = null)
    {
        return rtrim($str, $char);
    }
}

if (!function_exists('drewlabs_core_strings_ltrim')) {
    /**
     * Strip the $char character from the begin of the $str string
     *
     * @param string $str
     * @param string|null $char
     * @return string
     */
    function drewlabs_core_strings_ltrim($str, $char = null)
    {
        return ltrim($str, $char);
    }
}

if (!function_exists('drewlabs_core_strings_rand_md5')) {
    /**
     * Generate a random string using PHP md5() uniqid() and microtime() functions
     */
    function drewlabs_core_strings_rand_md5()
    {
        return md5(uniqid() . microtime());
    }
}

if (!function_exists('drewlabs_core_strings_replace')) {
    /**
     * Replace provided subjects in the provided string
     *
     * @param string $search
     * @param string $replacement
     * @param string|string[] $subject
     * @param int $count
     * @return string|string[]
     */
    function drewlabs_core_strings_replace($search, $replacement, $subject, $count = null)
    {
        return str_replace($search, $replacement, $subject, $count);
    }
}

if (!function_exists('drewlabs_core_strings_after')) {
    /**
     * Returns the string after the first occurence of the provided character
     *
     * @param string $character
     * @param string $haystack
     * @return string
     */
    function drewlabs_core_strings_after($character, $haystack)
    {
        if (!is_bool(strpos($haystack, $character)))
            return substr($haystack, strpos($haystack, $character) + strlen($character));
    }
}

if (!function_exists('drewlabs_core_strings_after_last')) {
    /**
     * Returns the string after the last occurence of the provided character
     *
     * @param string $character
     * @param string $haystack
     * @return string
     */
    function drewlabs_core_strings_after_last($character, $haystack)
    {
        if (!is_bool(\drewlabs_core_strings_strrevpos($haystack, $character)))
            return substr($haystack,  \drewlabs_core_strings_strrevpos($haystack, $character) + strlen($character));
    }
}

if (!function_exists('drewlabs_core_strings_before')) {
    /**
     * Returns the string before the first occurence of the provided character
     *
     * @param string $character
     * @param string $haystack
     * @return string
     */
    function drewlabs_core_strings_before($character, $haystack)
    {
        return substr($haystack, 0, strpos($haystack, $character));
    }
}

if (!function_exists('drewlabs_core_strings_before_last')) {
    /**
     * Returns the string before the last occurence of the provided character
     *
     * @param string $character
     * @param string $haystack
     * @return string
     */
    function drewlabs_core_strings_before_last($character, $haystack)
    {
        return substr($haystack, 0,  \drewlabs_core_strings_strrevpos($haystack, $character));
    }
}

if (!function_exists('drewlabs_core_strings_between')) {
    /**
     * Returns the string between the first occurence of both provided characters
     *
     * @param string $character
     * @param string $that
     * @param string $haystack
     * @return string
     */
    function drewlabs_core_strings_between($character, $that, $haystack)
    {
        return  \drewlabs_core_strings_before($that, \drewlabs_core_strings_after($character, $haystack));
    }
}

if (!function_exists('drewlabs_core_strings_between_last')) {
    /**
     * Returns the string between the last occurence of both provided characters
     *
     * @param string $character
     * @param string $that
     * @param string $haystack
     * @return string
     */
    function drewlabs_core_strings_between_last($character, $that, $haystack)
    {
        return \drewlabs_core_strings_after_last($character, \drewlabs_core_strings_before_last($that, $haystack));
    }
}

if (!function_exists('drewlabs_core_strings_strrevpos')) {
    /**
     * Return the provided string in the reverse order
     *
     * @param string $instr
     * @param string $needle
     * @return string
     */
    function drewlabs_core_strings_strrevpos($instr, $needle)
    {
        $rev_pos = strpos(strrev($instr), strrev($needle));
        if ($rev_pos === false) return false;
        else return strlen($instr) - $rev_pos - strlen($needle);
    }
}

if (!function_exists('drewlabs_core_strings_parse_tpl')) {

    /**
     * Simple template parsing function for replacing properties|keys of an associative
     * array by their corresponding values
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
     * @param string $str
     * @param array|object $data
     * @return string
     */
    function drewlabs_core_strings_parse_tpl($str, $data)
    {
        if (!is_array($data)) {
            $data = (array) $data;
        }
        $patterns = array();
        $replacements = array();
        // Foreach values in the data attributes
        foreach ($data as $key => $value) {
            # code...
            $patterns[] = '/(\{){2}[ ]?\$' . $key . '[ ]?(\}){2}/i';
            $replacements[] = $value;
        }
        return preg_replace($patterns, $replacements, $str);
    }
}
if (!function_exists('drewlabs_core_strings_value_or_nullable')) {

    /**
     * Convert an enpty striing into PHP Nullable type
     * @return string|null
     */
    function drewlabs_core_strings_value_or_nullable($value)
    {
        if (!(drewlabs_core_strings_is_str($value))) {
            throw new InvalidArgumentException(sprintf('Helper %s requires a valid PHP string', __FUNCTION__));
        }
        return trim($value) === '' ? $value : null;
    }
}
