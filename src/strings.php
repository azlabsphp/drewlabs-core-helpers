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
        return ('' === $needle) || (\mb_substr($haystack, 0, \mb_strlen($needle)) === $needle);
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
        return ('' === $needle) || (\mb_substr($haystack, -(int) (\mb_strlen($needle))) === $needle);
    }
}
if (!function_exists('drewlabs_core_strings_sanitize')) {

    /**
     * Removes some characters from the string.
     *
     * @param string $search
     * @param string $str_to_sanitize
     * @param string $replacement
     *
     * @return string|string[]
     */
    function drewlabs_core_strings_sanitize($search, $str_to_sanitize, $replacement = '')
    {
        return str_replace($search, $replacement, $str_to_sanitize);
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
        return \is_string($value);
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
    function drewlabs_core_strings_to_lower_case($value)
    {
        return \mb_strtolower($value);
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
        return \mb_strtoupper($value);
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
        return \ucfirst($value);
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
        if (null === $haystack) {
            return false;
        }
        // Code patch for searching for string directly without converting it to an array of character
        if (drewlabs_core_strings_is_str($needle)) {
            return '' !== $needle && false !== mb_strpos($haystack, $needle);
        }
        foreach ((array) $needle as $n) {
            if ('' !== $n && false !== mb_strpos($haystack, $n)) {
                return true;
            }
        }

        return false;
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
        return 0 === strcmp($lhs, $rhs) ? true : false;
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
        $entries = array_merge([], $values);
        return drewlabs_core_strings_from_array($entries, $separator);
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
        if (!is_array($values)) {
            throw new \RuntimeException('Error parsing value... Provides an array value as parameter');
        }

        return implode($delimiter, $values);
    }
}

if (!function_exists('drewlabs_core_strings_to_array')) {
    /**
     * Explode a string variable into an array.
     *
     * @param string $value
     * @param string $delimiter
     *
     * @return array
     */
    function drewlabs_core_strings_to_array(string $value, $delimiter = ',')
    {
        if (!drewlabs_core_strings_is_str($value)) {
            throw new \RuntimeException('Error parsing value... Provides a string value as parameter');
        }

        return \explode($delimiter, (string)$value);
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
        return \rtrim($str, $char);
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
        return \ltrim($str, $char);
    }
}

if (!function_exists('drewlabs_core_strings_rand_md5')) {
    /**
     * Generate a random string using PHP md5() uniqid() and microtime() functions.
     */
    function drewlabs_core_strings_rand_md5()
    {
        return md5(uniqid() . microtime());
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
        return \str_replace($search, $replacement, $subject, $count);
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
        if (!is_bool(\mb_strpos($haystack, $character))) {
            return \mb_substr($haystack, \mb_strpos($haystack, $character) + \mb_strlen($character));
        }

        return '';
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
        if (!is_bool(drewlabs_core_strings_strrevpos($haystack, $character))) {
            return \mb_substr($haystack, drewlabs_core_strings_strrevpos($haystack, $character) + \mb_strlen($character));
        }
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
        $pos = \mb_strpos($haystack, $character);
        if ($pos) {
            return \mb_substr($haystack, 0, $pos);
        }

        return '';
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
        return \mb_substr($haystack, 0, drewlabs_core_strings_strrevpos($haystack, $character));
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
        return drewlabs_core_strings_before($that, drewlabs_core_strings_after($character, $haystack));
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
        return drewlabs_core_strings_after_last($character, drewlabs_core_strings_before_last($that, $haystack));
    }
}

if (!function_exists('drewlabs_core_strings_strrevpos')) {
    /**
     * Return the provided string in the reverse order.
     *
     * @param string $instr
     * @param string $needle
     *
     * @return int|null
     */
    function drewlabs_core_strings_strrevpos($instr, $needle)
    {
        $rev_pos = \mb_strpos(strrev($instr), strrev($needle));
        if (false === $rev_pos) {
            return false;
        }

        return \mb_strlen($instr) - $rev_pos - \mb_strlen($needle);
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
        if (!is_array($data)) {
            $data = (array) $data;
        }
        $patterns = [];
        $replacements = [];
        // Foreach values in the data attributes
        foreach ($data as $key => $value) {
            // code...
            $patterns[] = '/(\{){2}[ ]?\$' . $key . '[ ]?(\}){2}/i';
            $replacements[] = $value;
        }

        return preg_replace($patterns, $replacements, $str);
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
        if (!(drewlabs_core_strings_is_str($value))) {
            throw new InvalidArgumentException(sprintf('Helper %s requires a valid PHP string', __FUNCTION__));
        }

        return '' === trim($value) ? null : $value;
    }
}

if (!function_exists('drewlabs_core_strings_as_camel_case')) {
    /**
     * Convert provided word into camel case.
     * If $capitalize_first_chr === true, It capitalize the first character of the
     * generated string as well
     *
     * @param string $str
     * @param boolean $capitalize_first_chr
     * @param string $delimiter
     * @return string
     */
    function drewlabs_core_strings_as_camel_case(string $str, $capitalize_first_chr = true, $delimiter = '_')
    {
        return drewlabs_core_fn_compose_array(
            static function ($params) {
                if (count($params) < 2) {
                    throw new \RuntimeException();
                }

                return str_replace($params[1], '', ucwords($params[0], $params[1]));
            },
            static function ($param) use ($capitalize_first_chr) {
                return !$capitalize_first_chr ? lcfirst($param) : $param;
            }
        )($str, $delimiter);
    }
}


if (!function_exists('drewlabs_core_strings_as_camel_case_regex')) {
    /**
     * Convert provided word into camel case.
     * If $capitalize_first_chr === true, It capitalize the first character of the
     * generated string as well
     *
     * @param string $str
     * @param boolean $capitalize_first_chr
     * @param string $delimiter
     * @return string
     */
    function drewlabs_core_strings_as_camel_case_regex(string $str, $capitalize_first_chr = true, $delimiter = '{[_]+}')
    {
        return drewlabs_core_fn_compose_array(
            static function ($params) {
                if (count($params) < 2) {
                    throw new \RuntimeException();
                }
                return preg_replace($params[1], '', ucwords($params[0], $params[1]));
            },
            static function ($param) use ($capitalize_first_chr) {
                return !$capitalize_first_chr ? lcfirst($param) : $param;
            }
        )($str, $delimiter);
    }
}

if (!function_exists('drewlabs_core_strings_as_snake_case')) {
    /**
     * Convert provided string into snake case
     *
     * @param string $str
     * @param string $delimiter
     * @return string
     */
    function drewlabs_core_strings_as_snake_case(string $str, $delimiter = '_', $delimiter_escape_char = '\\')
    {
        if ((null === $str) || empty($str)) {
            return $str;
        }
        return str_replace(
            ' ',
            '',
            str_replace(
                [\sprintf("%s%s", $delimiter_escape_char, $delimiter), $delimiter_escape_char],
                $delimiter,
                trim(
                    drewlabs_core_strings_to_lower_case(
                        preg_replace(
                            '/([A-Z])([a-z\d])/',
                            $delimiter . '$0',
                            preg_replace("/[$delimiter]/", $delimiter_escape_char, $str)
                        )
                    ),
                    $delimiter
                )
            )
        );
    }
}

if (!function_exists('drewlabs_core_strings_as_snake_case_regex')) {
    /**
     * Convert provided string into snake case using regular expression
     *
     * @param string $str
     * @param string $delimiter
     * @return string
     */
    function drewlabs_core_strings_as_snake_case_regex(string $str, $delimiter = '{[_]+}')
    {
        // Convert all capital letters to $delimiter + lowercaseLetter
        $str = preg_replace([' ', $delimiter], '', lcfirst($str));
        return \mb_strtolower(preg_replace('/([A-Z])([a-z\d])/', $delimiter . '\\0', $str));
    }
}

if (!function_exists('drewlabs_core_strings_is_upper')) {

    /**
     * Checks if a given character is upper case or lowercase.
     *
     * @return bool
     */
    function drewlabs_core_strings_is_upper($chr)
    {
        return (function_exists('ctype_upper') ? static function ($source) {
            return \ctype_upper($source);
        } : static function ($source) {
            \preg_match('/[A-Z]/', $source) ? true : false;
        })($chr);
    }
}

if (!function_exists('drewlabs_core_strings_cadena_rtf')) {
    /**
     * String to cadena rtf
     *
     * @param string $txt
     * @return string
     */
    function drewlabs_core_strings_cadena_rtf(string $txt)
    {
        $result = null;
        for ($pos = 0; $pos < \mb_strlen($txt); ++$pos) {
            $char = \mb_substr($txt, $pos, 1);
            if (!preg_match('/[A-Za-z1-9,.]/', $char)) {
                //unicode ord real!!!
                $k = \mb_convert_encoding($char, 'UCS-2LE', 'UTF-8');
                $k1 = ord(\mb_substr($k, 0, 1));
                $k2 = ord(\mb_substr($k, 1, 1));
                $ord = $k2 * 256 + $k1;
                if ($ord > 255) {
                    $result .= '\uc1\u' . $ord . '*';
                } elseif ($ord > 32768) {
                    $result .= '\uc1\u' . ($ord - 65535) . '*';
                } else {
                    $result .= "\\'" . dechex($ord);
                }
            } else {
                $result .= $char;
            }
        }

        return $result;
    }
}

if (!function_exists('drewlabs_core_strings_ordutf8')) {
    /**
     * String to UTF-8 encoded
     *
     * @param string $string
     * @param int $offset
     * @return string
     */
    function drewlabs_core_strings_ordutf8(string $string, &$offset)
    {
        $code = ord(\mb_substr($string, $offset, 1));
        if ($code >= 128) {
            if ($code < 224) {
                $bytesnumber = 2;
            } elseif ($code < 240) {
                $bytesnumber = 3;
            } elseif ($code < 248) {
                $bytesnumber = 4;
            }
            $codetemp = $code - 192 - ($bytesnumber > 2 ? 32 : 0) - ($bytesnumber > 3 ? 16 : 0);
            for ($i = 2; $i <= $bytesnumber; ++$i) {
                ++$offset;
                $code2 = ord(\mb_substr($string, $offset, 1)) - 128;
                $codetemp = $codetemp * 64 + $code2;
            }
            $code = $codetemp;
        }
        ++$offset;
        if ($offset >= \mb_strlen($string)) {
            $offset = -1;
        }

        return $code;
    }
}
