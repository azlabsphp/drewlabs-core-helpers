<?php

define('DREWLABS_CORE_DATETIME_YEARS_PER_CENTURY', 100);
define('DREWLABS_CORE_DATETIME_YEARS_PER_DECADE', 10);
define('DREWLABS_CORE_DATETIME_MONTHS_PER_YEAR', 12);
define('DREWLABS_CORE_DATETIME_MONTHS_PER_QUARTER', 3);
define('DREWLABS_CORE_DATETIME_WEEKS_PER_YEAR', 52);
define('DREWLABS_CORE_DATETIME_WEEKS_PER_MONTH', 4);
define('DREWLABS_CORE_DATETIME_DAYS_PER_WEEK', 7);
define('DREWLABS_CORE_DATETIME_HOURS_PER_DAY', 24);
define('DREWLABS_CORE_DATETIME_MINUTES_PER_HOUR', 60);
define('DREWLABS_CORE_DATETIME_SECONDS_PER_MINUTE', 60);

if (!function_exists('drewlabs_core_datetime_now')) {
    /**
     * Returns the current date-time based on user provided timezone
     * @param \DateTimeZone|string timezone
     * @return \DateTime
     */
    function drewlabs_core_datetime_now($timezone = null)
    {
        if (\drewlabs_core_strings_is_str($timezone)) {
            $timezone = new \DateTimeZone($timezone);
        }
        return new \DateTime(null, $timezone);
    }
}

if (!function_exists('drewlabs_core_datetime_is_future')) {
    /**
     * Checks if a given date time is a future date time
     * @param \DateTimeInterface|\DateTime $current_date
     * @return bool
     */
    function drewlabs_core_datetime_is_future($current_date)
    {
        return \drewlabs_core_datetime_is_greater_than($current_date, \drewlabs_core_datetime_now_with_tz());
    }
}

if (!function_exists('drewlabs_core_datetime_is_past')) {
    /**
     * Determines if the instance is in the past, ie. less (before) than now.
     * @param \DateTimeInterface|\DateTime $current_date
     * @return bool
     */
    function drewlabs_core_datetime_is_past($current_date)
    {
        return \drewlabs_core_datetime_is_less_than($current_date, \drewlabs_core_datetime_now_with_tz());
    }
}

if (!function_exists('drewlabs_core_datetime_from_timestamp')) {
    /**
     * Create a dateTime instance from timestamp
     * @param int $timestamp
     * @return \DateTime
     */
    function drewlabs_core_datetime_from_timestamp(int $timestamp)
    {
        return new \DateTime('@' . $timestamp);
    }
}

if (!function_exists('drewlabs_core_datetime_get_tz')) {
    /**
     * Get the timezone of a dateTime instance
     * @param \DateTime $value
     * @return \DateTimeZone
     */
    function drewlabs_core_datetime_get_tz(\DateTime $value)
    {
        return $value->getTimeZone();
    }
}

if (!function_exists('drewlabs_core_datetime_now_with_tz')) {
    /**
     * Return the current dateTime value alongs with the timezone
     * @return \DateTime|\DateTimeInterface
     */
    function drewlabs_core_datetime_now_with_tz()
    {
        return \drewlabs_core_datetime_now(\drewlabs_core_datetime_get_tz(new \DateTime));
    }
}

if (!function_exists('drewlabs_core_datetime_is_greater_than')) {
    /**
     * Date comparison function which returns true if the first date is greater that the other date
     * @param \DateTimeInterface|\DateTime $lhs
     * @param \DateTimeInterface|\DateTime $rhs
     * @return bool
     */
    function drewlabs_core_datetime_is_greater_than($lhs, $rhs)
    {
        return $lhs > $rhs;
    }
}

if (!function_exists('drewlabs_core_datetime_is_less_than')) {
    /**
     * Determines if the instance is less (before) than another
     *
     * @param \DateTimeInterface|\DateTime $lhs
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
     * Add user provided minutes to the datetime instance
     * @param \DateTime $date
     * @param int $minutes
     * @return \DateTime|\DateTimeInterface
     */
    function drewlabs_core_datetime_add_minutes($date, $minutes = 0)
    {
        return $date->modify((int) $minutes . ' minute');
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
        $date = \drewlabs_core_datetime_resolve($current_date, $date);

        return \drewlabs_core_datetime_is_greater_than($current_date, $date) ? $current_date : $date;
    }
}

if (!function_exists('drewlabs_core_datetime_is_same')) {
    /**
     * Compares the formatted values of the two dates.
     *
     * @param \DateTimeInterface $date
     * @param \DateTimeInterface|null $otherDate   The instance to compare with or null to use current day.
     * @param string                                 $format The date formats to compare.
     *
     * @throws \InvalidArgumentException
     *
     * @return bool
     */
    function drewlabs_core_datetime_is_same($date, $otherDate = null, $format = 'c')
    {
        if (!isset($otherDate)) {
            $otherDate = \drewlabs_core_datetime_now_with_tz();
        }
        \drewlabs_core_datetime_expect_date_time($otherDate, 'null');
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
    function drewlabs_core_datetime_expect_date_time($date, $other = array())
    {
        $message = 'Expected type : ';
        foreach ((array) $other as $expect) {
            $message .= "{$expect}, ";
        }

        if (!($date instanceof \DateTime) && !($date instanceof \DateTimeInterface)) {
            throw new \InvalidArgumentException(
                $message . 'DateTime or DateTimeInterface, ' . (is_object($date) ? get_class($date) : gettype($date)) . ' given'
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
     * @return \DateTimeInterface|DateTime
     */
    function drewlabs_core_datetime_resolve($current_date, $date = null)
    {
        if (!$date) {
            return \drewlabs_core_datetime_now_with_tz();
        }
        if (is_string($date)) {
            return new \DateTime($date, $current_date->getTimezone());
        }
        \drewlabs_core_datetime_expect_date_time($date, array('null', 'String'));
        return ($date instanceof \DateTime) || ($date instanceof \DateTimeInterface) ? $date : \drewlabs_core_datetime_make_date($date);
    }
}

if (!function_exists('drewlabs_core_datetime_make_date')) {
    /**
     * Create a DateUtils from a DateTime.
     *
     * @param \DateTime|\DateTimeInterface $date
     *
     * @return \DateTimeInterface|\DateTime
     */
    function drewlabs_core_datetime_make_date($date)
    {
        if (($date instanceof \DateTimeInterface) || ($date instanceof \DateTime)) {
            return clone $date;
        }
        \drewlabs_core_datetime_expect_date_time($date);
        return new \DateTime($date->format('Y-m-d H:i:s.u'), $date->getTimezone());
    }
}

if (!function_exists('drewlabs_core_datetime_hrs_diff')) {
    /**
     * Get the difference in hours.
     * @param \DateTime|string|null $date
     * @param \DateTime|string|null $date
     * @param bool $exact Get the exact of the difference
     *
     * @return int
     */
    function drewlabs_core_datetime_hrs_diff($source, $date = null, $exact = true)
    {
        return (int) (\drewlabs_core_datetime_secs_diff($source, $date, $exact) / DREWLABS_CORE_DATETIME_SECONDS_PER_MINUTE / DREWLABS_CORE_DATETIME_MINUTES_PER_HOUR);
    }
}

if (!function_exists('drewlabs_core_datetime_min_diff')) {
    /**
     * Get the difference in minutes.
     *
     * @param \DateTimeInterface $date
     * @param \DateTimeInterface|null $date
     * @param bool $exact Get the exact of the difference
     *
     * @return int
     */
    function drewlabs_core_datetime_min_diff($date = null, $exact = true)
    {
        return (int) (\drewlabs_core_datetime_secs_diff($date, $exact) / DREWLABS_CORE_DATETIME_SECONDS_PER_MINUTE);
    }
}

if (!function_exists('drewlabs_core_datetime_secs_diff')) {
    /**
     * Get the difference in seconds.
     *
     * @param \DateTime $source
     * @param \DateTime|null $date
     * @param bool $exact Get the exact of the difference
     *
     * @return int
     */
    function drewlabs_core_datetime_secs_diff($source, $date = null, $exact = true)
    {
        $diff = $source->diff(\drewlabs_core_datetime_resolve($source, $date));
        $value = $diff->days * DREWLABS_CORE_DATETIME_HOURS_PER_DAY * DREWLABS_CORE_DATETIME_MINUTES_PER_HOUR * DREWLABS_CORE_DATETIME_SECONDS_PER_MINUTE +
            $diff->h * DREWLABS_CORE_DATETIME_MINUTES_PER_HOUR * DREWLABS_CORE_DATETIME_SECONDS_PER_MINUTE +
            $diff->i * DREWLABS_CORE_DATETIME_SECONDS_PER_MINUTE +
            $diff->s;

        return $exact || !$diff->invert ? $value : -$value;
    }
}
