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

class Rand
{
    /**
     * Generate a random api key.
     *
     * @return string|string[]
     */
    public static function key(int $bytes)
    {
        return Str::replace(
            '=',
            '',
            Str::replace(
                [\chr(92), '+', \chr(47), \chr(38)],
                '.',
                base64_encode(openssl_random_pseudo_bytes($bytes))
            )
        );
    }

    /**
     * Generate a new date with added value.
     *
     * @param bool $date
     *
     * @return string
     */
    public static function dateTime(string $datetime, $date = false)
    {
        $timestamp = strtotime($datetime, time());

        return true === $date ?
            date('Y-m-d', $timestamp) :
            date('Y-m-d H:i:s', $timestamp);
    }

    /**
     * @throws \TypeError
     * @throws \Error
     * @throws \Exception
     *
     * @return mixed
     */
    public static function str(int $length = 16)
    {
        if (\is_callable('str_rand')) {
            return \call_user_func('str_rand', $length);
        }
        $x = '';
        for ($i = 1; $i <= $length; ++$i) {
            $x .= dechex(static::int(0, 255));
        }

        return substr($x, 0, $length);
    }

    /**
     * @throws \TypeError
     * @throws \Error
     * @throws \Exception
     *
     * @return string
     */
    public static function secret(int $iterations = 4)
    {
        $tmp = '';
        for ($index = $iterations; $index > 0; --$index) {
            $tmp .= static::str($index);
        }

        return $tmp;
    }

    /**
     * @throws \Exception
     *
     * @return string
     */
    public function bytes(int $bytes)
    {
        if (\function_exists('random_bytes')) {
            return random_bytes($bytes);
        }
        /*
         * If we reach here, PHP has failed us.
         */
        throw new \Exception(
            'Could not gather sufficient random data'
        );
    }

    /**
     * Random::* Compatibility Library for using the new PHP 7 random_* API in PHP 5 projects.
     *
     * @param int $min
     * @param int $max
     *
     * @return int
     */
    public static function int($min, $max)
    {
        if (\function_exists('random_int')) {
            return \call_user_func('random_int', $min, $max);
        }
        /*
         * Type and input logic checks
         *
         * If you pass it a float in the range (~PHP_INT_MAX, PHP_INT_MAX)
         * (non-inclusive), it will sanely cast it to an int. If you it's equal to
         * ~PHP_INT_MAX or PHP_INT_MAX, we let it fail as not an integer. Floats
         * lose precision, so the <= and => operators might accidentally let a float
         * through.
         */

        try {
            /** @var int $min */
            $min = static::intval($min);
        } catch (\TypeError $ex) {
            throw new \TypeError(
                'random_int(): $min must be an integer'
            );
        }

        try {
            /** @var int $max */
            $max = static::intval($max);
        } catch (\TypeError $ex) {
            throw new \TypeError(
                'random_int(): $max must be an integer'
            );
        }

        /*
         * Now that we've verified our weak typing system has given us an integer,
         * let's validate the logic then we can move forward with generating random
         * integers along a given range.
         */
        if ($min > $max) {
            throw new \Error(
                'Minimum value must be less than or equal to the maximum value'
            );
        }

        if ($max === $min) {
            return (int) $min;
        }

        /**
         * Initialize variables to 0.
         *
         * We want to store:
         * $bytes => the number of random bytes we need
         * $mask => an integer bitmask (for use with the &) operator
         *          so we can minimize the number of discards
         */
        $attempts = $bits = $bytes = $mask = $valueShift = 0;
        /** @var int $attempts */
        /** @var int $bits */
        /** @var int $bytes */
        /** @var int $mask */
        /** @var int $valueShift */

        /**
         * At this point, $range is a positive number greater than 0. It might
         * overflow, however, if $max - $min > PHP_INT_MAX. PHP will cast it to
         * a float and we will lose some precision.
         *
         * @var int|float $range
         */
        $range = $max - $min;

        /*
         * Test for integer overflow:
         */
        if (!\is_int($range)) {

            /**
             * Still safely calculate wider ranges.
             * Provided by @CodesInChaos, @oittaa.
             *
             * @ref https://gist.github.com/CodesInChaos/03f9ea0b58e8b2b8d435
             *
             * We use ~0 as a mask in this case because it generates all 1s
             *
             * @ref https://eval.in/400356 (32-bit)
             * @ref http://3v4l.org/XX9r5  (64-bit)
             */
            $bytes = \PHP_INT_SIZE;
            /** @var int $mask */
            $mask = ~0;
        } else {

            /**
             * $bits is effectively ceil(log($range, 2)) without dealing with
             * type juggling.
             */
            while ($range > 0) {
                if (0 === $bits % 8) {
                    ++$bytes;
                }
                ++$bits;
                $range >>= 1;
                /** @var int $mask */
                $mask = $mask << 1 | 1;
            }
            $valueShift = $min;
        }

        /** @var int $val */
        $val = 0;
        /*
         * Now that we have our parameters set up, let's begin generating
         * random integers until one falls between $min and $max
         */
        /* @psalm-suppress RedundantCondition */
        do {
            /*
             * The rejection probability is at most 0.5, so this corresponds
             * to a failure probability of 2^-128 for a working RNG
             */
            if ($attempts > 128) {
                throw new \Exception(
                    'random_int: RNG is broken - too many rejections'
                );
            }

            /**
             * Let's grab the necessary number of random bytes.
             */
            $randomByteString = random_bytes($bytes);

            /*
             * Let's turn $randomByteString into an integer
             *
             * This uses bitwise operators (<< and |) to build an integer
             * out of the values extracted from ord()
             *
             * Example: [9F] | [6D] | [32] | [0C] =>
             *   159 + 27904 + 3276800 + 201326592 =>
             *   204631455
             */
            $val &= 0;
            for ($i = 0; $i < $bytes; ++$i) {
                $val |= \ord($randomByteString[$i]) << ($i * 8);
            }
            /* @var int $val */

            /*
             * Apply mask
             */
            $val &= $mask;
            $val += $valueShift;

            ++$attempts;
            /*
             * If $val overflows to a floating point number,
             * ... or is larger than $max,
             * ... or smaller than $min,
             * then try again.
             */
        } while (!\is_int($val) || $val > $max || $val < $min);

        return (int) $val;
    }

    private static function intval($number, $fail_open = false)
    {
        if (\is_int($number) || \is_float($number)) {
            $number += 0;
        } elseif (is_numeric($number)) {
            /* @psalm-suppress InvalidOperand */
            $number += 0;
        }
        /** @var int|float $number */
        if (
            \is_float($number)
            &&
            $number > ~\PHP_INT_MAX
            &&
            $number < \PHP_INT_MAX
        ) {
            $number = (int) $number;
        }

        if (\is_int($number)) {
            return (int) $number;
        } elseif (!$fail_open) {
            throw new \TypeError(
                'Expected an integer.'
            );
        }

        return $number;
    }
}
