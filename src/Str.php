<?php

namespace Drewlabs\Core\Helpers;

use InvalidArgumentException;
use RuntimeException;

class Str
{
    /**
     * Generates a random string
     * 
     * @param null|int $length 
     * @param bool $capitalize 
     * @return string|false 
     */
    public static function rand(?int $length, $capitalize = true)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        return $capitalize ? strtoupper(
            substr(
                str_shuffle($chars),
                0,
                $length ?? 12
            )
        ) :
            substr(
                str_shuffle($chars),
                0,
                $length ?? 12
            );
    }

    /**
     * Check if a given string starts with a substring.
     *
     * @param string $haystack
     * @param string $needle
     *
     * @return bool
     */
    public static function startsWith(string $haystack, string $needle)
    {
        return ('' === $needle) || (mb_substr($haystack, 0, mb_strlen($needle)) === $needle);
    }

    /**
     * Check if a given string ends with a substring.
     *
     * @param string $haystack
     * @param string $needle
     *
     * @return bool
     */
    public static function endsWith(string $haystack, string $needle)
    {
        return ('' === $needle) || (mb_substr($haystack, -(int) (mb_strlen($needle))) === $needle);
    }

    /**
     * Removes some characters from the string.
     *
     * @param array|string $search
     * @param array|string $haystack
     * @param array|string $replacement
     *
     * @return string|string[]
     */
    public static function sanitize($search, $haystack, $replacement = '')
    {
        return str_replace($search, $replacement, $haystack);
    }


    /**
     * Check if a given variable is a string.
     *
     * @param string $value
     *
     * @return bool
     */
    public static function isStr($value)
    {
        return is_string($value);
    }


    /**
     * Converts string to lowercase.
     *
     * @param string $value
     *
     * @return string
     */
    public static function lower(string $value)
    {
        if (function_exists('mb_strtolower')) {
            return mb_strtolower($value);
        }
        return strtolower($value);
    }


    /**
     * Converts string to uppercase.
     *
     * @param string $value
     *
     * @return string
     */
    public static function upper(string $value)
    {
        if (function_exists('mb_strtoupper')) {
            return mb_strtoupper($value);
        }
        return strtoupper($value);
    }


    /**
     * Converts first character of a string to uppercase.
     *
     * @param string $value
     *
     * @return string
     */
    public static function capitalize(string $value)
    {
        return ucfirst($value);
    }

    /**
     * Determine if a given string contains a given substring.
     *
     * @param string       $haystack
     * @param string|array $needle
     *
     * @return bool
     */
    public static function contains(string $haystack, $needle)
    {
        if (null === $haystack) {
            return false;
        }
        // Code patch for searching for string directly without converting it to an array of character
        if (Str::isStr($needle)) {
            return '' !== $needle && false !== mb_strpos($haystack, $needle);
        }
        foreach ((array) $needle as $n) {
            if ('' !== $n && false !== mb_strpos($haystack, $n)) {
                return true;
            }
        }

        return false;
    }


    /**
     * Checks if two strings are same.
     *
     * @param string $lhs
     * @param string $rhs
     *
     * @return bool
     */
    public static function same(string $lhs, string $rhs)
    {
        return 0 === strcmp($lhs, $rhs) ? true : false;
    }

    /**
     * Glue together other function parameters with the first {$separator} parameter.
     *
     * @param string      $separator
     * @param array|mixed ...$values
     *
     * @return string
     */
    public static function concat(string $separator, ...$values)
    {
        $entries = array_merge([], $values);

        return Str::join($entries, $separator);
    }

    /**
     * 
     * @param array $values 
     * @param string $delimiter 
     * @return string 
     * @throws RuntimeException 
     */
    public static function join(array $values, $delimiter = ',')
    {
        if (!is_array($values)) {
            throw new \RuntimeException('Error parsing value... Provides an array value as parameter');
        }
        return implode($delimiter, $values);
    }

    /**
     * Explode a string variable into an array.
     * 
     * @param string $value 
     * @param string $delimiter 
     * @return string[]|false 
     * @throws RuntimeException 
     */
    public static function split(string $value, $delimiter = ',')
    {
        if (!Str::isStr($value)) {
            throw new \RuntimeException('Error parsing value... Provides a string value as parameter');
        }

        return explode($delimiter, (string) $value);
    }


    /**
     * Strip the $char character from the end of the $str string.
     *
     * @param string      $haystack
     * @param string|null $char
     *
     * @return string
     */
    public static function rtrim($haystack, $char = null)
    {
        return rtrim($haystack, $char);
    }

    /**
     * Strip the $char character from the begin of the $str string.
     *
     * @param string      $haystack
     * @param string|null $char
     *
     * @return string
     */
    public static function ltrim($haystack, $char = null)
    {
        return ltrim($haystack, $char);
    }


    /**
     * Generate a random string using PHP md5() uniqid() and microtime() functions.
     */
    public static function md5()
    {
        return md5(uniqid() . microtime());
    }


    /**
     * Replace provided subjects in the provided string.
     *
     * @param string|string[]          $search
     * @param string|string[]          $replacement
     * @param string|string[] $subject
     * @param int             $count
     *
     * @return string|string[]
     */
    public static function replace($search, $replacement, $subject, ?int &$count = null)
    {
        return str_replace($search, $replacement, $subject, $count);
    }


    /**
     * Returns the string after the first occurence of the provided character.
     *
     * @param string $character
     * @param string $haystack
     *
     * @return string
     */
    public static function after(string $character, string $haystack)
    {
        if (!is_bool(mb_strpos($haystack, $character))) {
            return mb_substr($haystack, mb_strpos($haystack, $character) + mb_strlen($character));
        }

        return '';
    }


    /**
     * Returns the string after the last occurence of the provided character.
     *
     * @param string $character
     * @param string $haystack
     *
     * @return string
     */
    public static function afterLast(string $character, string $haystack)
    {
        if (!is_bool(Str::strrevpos($haystack, $character))) {
            return mb_substr($haystack, Str::strrevpos($haystack, $character) + mb_strlen($character));
        }
    }


    /**
     * Returns the string before the first occurence of the provided character.
     *
     * @param string $character
     * @param string $haystack
     *
     * @return string
     */
    public static function before(string $character, string $haystack)
    {
        $pos = mb_strpos($haystack, $character);
        if ($pos) {
            return mb_substr($haystack, 0, $pos);
        }

        return '';
    }


    /**
     * Returns the string before the last occurence of the provided character.
     *
     * @param string $character
     * @param string $haystack
     *
     * @return string
     */
    public static function beforeLast(string $character, string $haystack)
    {
        return mb_substr($haystack, 0, Str::strrevpos($haystack, $character));
    }


    /**
     * Returns the string between the first occurence of both provided characters.
     *
     * @param string $character
     * @param string $that
     * @param string $haystack
     *
     * @return string
     */
    public static function between(string $character, string $that, string $haystack)
    {
        return Str::before($that, Str::after($character, $haystack));
    }


    /**
     * Returns the string between the last occurence of both provided characters.
     *
     * @param string $character
     * @param string $that
     * @param string $haystack
     *
     * @return string
     */
    public static function betweenLast(string $character, string $that, string $haystack)
    {
        return Str::afterLast($character, Str::beforeLast($that, $haystack));
    }


    /**
     * Return the provided string in the reverse order.
     *
     * @param string $haystack
     * @param string $needle
     *
     * @return int|null
     */
    public static function strrevpos(string $haystack, string $needle)
    {
        $rev_pos = mb_strpos(strrev($haystack), strrev($needle));
        if (false === $rev_pos) {
            return false;
        }

        return mb_strlen($haystack) - $rev_pos - mb_strlen($needle);
    }



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
     * @param string       $template
     * @param array|object $data
     *
     * @return string
     */
    public static function parse(string $template, $data)
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

        return preg_replace($patterns, $replacements, $template);
    }


    /**
     * Convert an enpty striing into PHP Nullable type.
     *
     * @return string|null
     */
    public static function valueOrNull($value)
    {
        if (!(Str::isStr($value))) {
            throw new InvalidArgumentException(sprintf('Helper %s requires a valid PHP string', __FUNCTION__));
        }

        return '' === trim($value) ? null : $value;
    }


    /**
     * Convert provided word into camel case.
     * If $capitalize_first_chr === true, It capitalize the first character of the
     * generated string as well.
     *
     * @param bool   $firstcapital
     * @param string $delimiter
     *
     * @return string
     */
    public static function camelize(string $haystack, $firstcapital = true, $delimiter = '_')
    {
        return drewlabs_core_fn_compose_array(
            static function ($params) {
                if (count($params) < 2) {
                    throw new \RuntimeException();
                }

                return str_replace($params[1], '', ucwords($params[0], $params[1]));
            },
            static function ($param) use ($firstcapital) {
                return !$firstcapital ? lcfirst($param) : $param;
            }
        )($haystack, $delimiter);
    }


    /**
     * Convert provided word into camel case.
     * If $capitalize_first_chr === true, It capitalize the first character of the
     * generated string as well.
     *
     * @param bool   $firstcapital
     * @param string $delimiter
     *
     * @return string
     */
    public static function regexCamelize(string $haystack, $firstcapital = true, $delimiter = '{[_]+}')
    {
        return drewlabs_core_fn_compose_array(
            static function ($params) {
                if (count($params) < 2) {
                    throw new \RuntimeException();
                }

                return preg_replace($params[1], '', ucwords($params[0], $params[1]));
            },
            static function ($param) use ($firstcapital) {
                return !$firstcapital ? lcfirst($param) : $param;
            }
        )($haystack, $delimiter);
    }


    /**
     * Convert provided string into snake case.
     *
     * @param string $delimiter
     *
     * @return string
     */
    public static function snakeCase(string $haystack, $delimiter = '_', $delimiter_escape_char = '\\')
    {
        if ((null === $haystack) || empty($haystack)) {
            return $haystack;
        }

        return str_replace(
            ' ',
            '',
            str_replace(
                [sprintf('%s%s', $delimiter_escape_char, $delimiter), $delimiter_escape_char],
                $delimiter,
                trim(
                    Str::lower(
                        preg_replace(
                            '/([A-Z])([a-z\d])/',
                            $delimiter . '$0',
                            preg_replace("/[$delimiter]/", $delimiter_escape_char, $haystack)
                        )
                    ),
                    $delimiter
                )
            )
        );
    }


    /**
     * Convert provided string into snake case using regular expression.
     *
     * @param string $delimiter
     *
     * @return string
     */
    public static function regexSnakeCase(string $haystack, $delimiter = '{[_]+}')
    {
        // Convert all capital letters to $delimiter + lowercaseLetter
        $haystack = preg_replace([' ', $delimiter], '', lcfirst($haystack));

        return mb_strtolower(preg_replace('/([A-Z])([a-z\d])/', $delimiter . '\\0', $haystack));
    }

    /**
     * Checks if a given character is upper case or lowercase.
     * 
     * @param mixed $chr 
     * @return bool 
     */
    public static function isUpper($chr)
    {
        return (function_exists('ctype_upper') ? static function ($source) {
            return ctype_upper($source);
        } : static function ($source) {
            preg_match('/[A-Z]/', $source) ? true : false;
        })($chr);
    }


    /**
     * String to cadena rtf.
     *
     * @return string
     */
    public static function cadenartf(string $txt)
    {
        $result = null;
        for ($pos = 0; $pos < mb_strlen($txt); ++$pos) {
            $char = mb_substr($txt, $pos, 1);
            if (!preg_match('/[A-Za-z1-9,.]/', $char)) {
                //unicode ord real!!!
                $k = mb_convert_encoding($char, 'UCS-2LE', 'UTF-8');
                $k1 = ord(mb_substr($k, 0, 1));
                $k2 = ord(mb_substr($k, 1, 1));
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


    /**
     * String to UTF-8 encoded.
     *
     * @param int $offset
     *
     * @return string
     */
    public static function ordutf8(string $haystack, &$offset)
    {
        $code = ord(mb_substr($haystack, $offset, 1));
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
                $code2 = ord(mb_substr($haystack, $offset, 1)) - 128;
                $codetemp = $codetemp * 64 + $code2;
            }
            $code = $codetemp;
        }
        ++$offset;
        if ($offset >= mb_strlen($haystack)) {
            $offset = -1;
        }

        return $code;
    }
}
