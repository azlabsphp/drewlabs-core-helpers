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

if (!defined('DREWLABS_CORE_DATETIME_YEARS_PER_CENTURY')) {
    define('DREWLABS_CORE_DATETIME_YEARS_PER_CENTURY', 100);
}
if (!defined('DREWLABS_CORE_DATETIME_YEARS_PER_DECADE')) {
    define('DREWLABS_CORE_DATETIME_YEARS_PER_DECADE', 10);
}
if (!defined('DREWLABS_CORE_DATETIME_MONTHS_PER_YEAR')) {
    define('DREWLABS_CORE_DATETIME_MONTHS_PER_YEAR', 12);
}
if (!defined('DREWLABS_CORE_DATETIME_MONTHS_PER_QUARTER')) {
    define('DREWLABS_CORE_DATETIME_MONTHS_PER_QUARTER', 3);
}
if (!defined('DREWLABS_CORE_DATETIME_WEEKS_PER_YEAR')) {
    define('DREWLABS_CORE_DATETIME_WEEKS_PER_YEAR', 52);
}
if (!defined('DREWLABS_CORE_DATETIME_WEEKS_PER_MONTH')) {
    define('DREWLABS_CORE_DATETIME_WEEKS_PER_MONTH', 4);
}
if (!defined('DREWLABS_CORE_DATETIME_DAYS_PER_WEEK')) {
    define('DREWLABS_CORE_DATETIME_DAYS_PER_WEEK', 7);
}
if (!defined('DREWLABS_CORE_DATETIME_HOURS_PER_DAY')) {
    define('DREWLABS_CORE_DATETIME_HOURS_PER_DAY', 24);
}
if (!defined('DREWLABS_CORE_DATETIME_MINUTES_PER_HOUR')) {
    define('DREWLABS_CORE_DATETIME_MINUTES_PER_HOUR', 60);
}
if (!defined('DREWLABS_CORE_DATETIME_SECONDS_PER_MINUTE')) {
    define('DREWLABS_CORE_DATETIME_SECONDS_PER_MINUTE', 60);
}

if (!function_exists('drewlabs_core_datetime_now')) {
    /**
     * Returns the current date-time based on user provided timezone.
     *
     * @param \DateTimeZone|string timezone
     *
     * @return \DateTimeInterface
     */
    function drewlabs_core_datetime_now($timezone = null)
    {
        if (drewlabs_core_strings_is_str($timezone)) {
            $timezone = new \DateTimeZone($timezone);
        }

        return new \DateTimeImmutable('now', $timezone);
    }
}

if (!function_exists('drewlabs_core_datetime_is_future')) {
    /**
     * Checks if a given date time is a future date time.
     *
     * @param \DateTimeInterface $current_date
     *
     * @return bool
     */
    function drewlabs_core_datetime_is_future($current_date)
    {
        return drewlabs_core_datetime_is_greater_than($current_date, drewlabs_core_datetime_now_with_tz());
    }
}

if (!function_exists('drewlabs_core_datetime_is_past')) {
    /**
     * Determines if the instance is in the past, ie. less (before) than now.
     *
     * @param \DateTimeInterface $current_date
     *
     * @return bool
     */
    function drewlabs_core_datetime_is_past($current_date)
    {
        return drewlabs_core_datetime_is_less_than($current_date, drewlabs_core_datetime_now_with_tz());
    }
}

if (!function_exists('drewlabs_core_datetime_from_timestamp')) {
    /**
     * Create a dateTime instance from timestamp.
     *
     * @return \DateTimeInterface
     */
    function drewlabs_core_datetime_from_timestamp(int $timestamp)
    {
        return new \DateTimeImmutable('@'.$timestamp);
    }
}

if (!function_exists('drewlabs_core_datetime_get_tz')) {
    /**
     * Get the timezone of a dateTime instance.
     *
     * @param \DateTimeInterface $value
     *
     * @return \DateTimeZone
     */
    function drewlabs_core_datetime_get_tz(DateTimeInterface $value)
    {
        return $value->getTimeZone();
    }
}

if (!function_exists('drewlabs_core_datetime_now_with_tz')) {
    /**
     * Return the current dateTime value alongs with the timezone.
     *
     * @return \DateTimeInterface
     */
    function drewlabs_core_datetime_now_with_tz()
    {
        return drewlabs_core_datetime_now(drewlabs_core_datetime_get_tz(new \DateTime()));
    }
}

if (!function_exists('drewlabs_core_datetime_is_greater_than')) {
    /**
     * Date comparison function which returns true if the first date is greater that the other date.
     *
     * @param \DateTimeInterface $lhs
     * @param \DateTimeInterface $rhs
     *
     * @return bool
     */
    function drewlabs_core_datetime_is_greater_than($lhs, $rhs)
    {
        return $lhs > $rhs;
    }
}

if (!function_exists('drewlabs_core_datetime_is_less_than')) {
    /**
     * Determines if the instance is less (before) than another.
     *
     * @param \DateTimeInterface       $lhs
     * @param \DateTimeInterface|mixed $rhs
     *
     * @return bool
     */
    function drewlabs_core_datetime_is_less_than($lhs, $rhs)
    {
        return $lhs < $rhs;
    }
}

if (!function_exists('drewlabs_core_datetime_add_minutes')) {
    /**
     * Add user provided minutes to the datetime instance.
     *
     * @param \DateTimeImmutable|\DateTime $date
     * @param int                          $minutes
     *
     * @return \DateTimeInterface
     */
    function drewlabs_core_datetime_add_minutes($date, $minutes = 0)
    {
        return $date->modify((int) $minutes.' minute');
    }
}

if (!function_exists('drewlabs_core_datetime_max')) {
    /**
     * Get the maximum instance between a given instance (default now) and the current instance.
     *
     * @param \DateTimeInterface|string|null $date
     *
     * @return \DateTimeInterface
     */
    function drewlabs_core_datetime_max($current_date, $date = null)
    {
        $date = drewlabs_core_datetime_resolve($current_date, $date);

        return drewlabs_core_datetime_is_greater_than($current_date, $date) ? $current_date : $date;
    }
}

if (!function_exists('drewlabs_core_datetime_is_same')) {
    /**
     * Compares the formatted values of the two dates.
     *
     * @param \DateTimeInterface      $date
     * @param \DateTimeInterface|null $otherDate the instance to compare with or null to use current day
     * @param string                  $format    the date formats to compare
     *
     * @throws \InvalidArgumentException
     *
     * @return bool
     */
    function drewlabs_core_datetime_is_same($date, $otherDate = null, $format = 'c')
    {
        if (!isset($otherDate)) {
            $otherDate = drewlabs_core_datetime_now_with_tz();
        }
        drewlabs_core_datetime_expect_date_time($otherDate, 'null');

        return $date->format($format) === $otherDate->format($format);
    }
}

if (!function_exists('drewlabs_core_datetime_expect_date_time')) {
    /**
     * Throws an exception if the given object is not a DateTime and does not implement DateTimeInterface
     * and not in $other.
     *
     * @param mixed        $date
     * @param string|array $other
     *
     * @throws \InvalidArgumentException
     */
    function drewlabs_core_datetime_expect_date_time($date, $other = [])
    {
        $message = 'Expected type : ';
        foreach ((array) $other as $expect) {
            $message .= "{$expect}, ";
        }

        if (!($date instanceof \DateTimeInterface)) {
            throw new \InvalidArgumentException(
                $message.'DateTime or DateTimeInterface, '.(is_object($date) ? get_class($date) : gettype($date)).' given'
            );
        }
    }
}

if (!function_exists('drewlabs_core_datetime_resolve')) {
    /**
     * Return the DateTime instance passed through, a now instance in the same timezone
     * if null given or parse the input if string given.
     *
     * @param \DateTimeInterface|string|null $current_date
     * @param \DateTimeInterface|string|null $date
     *
     * @return \DateTimeInterface
     */
    function drewlabs_core_datetime_resolve($current_date, $date = null)
    {
        if (null === $date) {
            return drewlabs_core_datetime_now_with_tz();
        }
        if (is_string($date)) {
            return new \DateTimeImmutable($date, $current_date->getTimezone());
        }

        return drewlabs_core_datetime_make_date($date);
    }
}

if (!function_exists('drewlabs_core_datetime_make_date')) {
    /**
     * Create a DateUtils from a DateTime.
     *
     * @param \DateTimeInterface|string|null $date
     *
     * @return \DateTimeInterface
     */
    function drewlabs_core_datetime_make_date($date = null, ?DateTimeZone $timezone = null)
    {
        if ($date instanceof \DateTimeInterface) {
            return clone $date;
        }
        drewlabs_core_datetime_expect_date_time($date, ['string', 'null']);

        return new \DateTimeImmutable($date ?? 'now', $timezone);
    }
}

if (!function_exists('drewlabs_core_datetime_hrs_diff')) {
    /**
     * Get the difference in hours.
     *
     * @param \DateTimeInterface|string|null $source_date
     * @param \DateTimeInterface|string|null $date
     * @param bool                           $exact       Get the exact of the difference
     *
     * @return int
     */
    function drewlabs_core_datetime_hrs_diff($source_date, $date = null, $exact = true)
    {
        return (int) (drewlabs_core_datetime_secs_diff($source_date, $date, $exact) / DREWLABS_CORE_DATETIME_SECONDS_PER_MINUTE / DREWLABS_CORE_DATETIME_MINUTES_PER_HOUR);
    }
}

if (!function_exists('drewlabs_core_datetime_min_diff')) {
    /**
     * Get the difference in minutes.
     *
     * @param \DateTimeInterface      $source_date
     * @param \DateTimeInterface|null $date
     * @param bool                    $exact       Get the exact of the difference
     *
     * @return int
     */
    function drewlabs_core_datetime_min_diff($source_date, $date = null, $exact = true)
    {
        return (int) (drewlabs_core_datetime_secs_diff($source_date, $date, $exact) / DREWLABS_CORE_DATETIME_SECONDS_PER_MINUTE);
    }
}

if (!function_exists('drewlabs_core_datetime_secs_diff')) {
    /**
     * Get the difference in seconds.
     *
     * @param \DateTimeInterface      $source
     * @param \DateTimeInterface|null $date
     * @param bool                    $exact  Get the exact of the difference
     *
     * @return int
     */
    function drewlabs_core_datetime_secs_diff($source, $date = null, $exact = true)
    {
        $diff = $source->diff(drewlabs_core_datetime_resolve($source, $date));
        $value = $diff->days * DREWLABS_CORE_DATETIME_HOURS_PER_DAY * DREWLABS_CORE_DATETIME_MINUTES_PER_HOUR * DREWLABS_CORE_DATETIME_SECONDS_PER_MINUTE +
            $diff->h * DREWLABS_CORE_DATETIME_MINUTES_PER_HOUR * DREWLABS_CORE_DATETIME_SECONDS_PER_MINUTE +
            $diff->i * DREWLABS_CORE_DATETIME_SECONDS_PER_MINUTE +
            $diff->s;

        return $exact || !$diff->invert ? $value : -$value;
    }
}
