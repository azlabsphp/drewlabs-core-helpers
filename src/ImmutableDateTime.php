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

use DateTimeInterface;

final class ImmutableDateTime
{
    /**
     * @var int
     */
    const _YEARS_PER_CENTURY = 100;

    /**
     * @var int
     */
    const _YEARS_PER_DECADE = 10;

    /**
     * @var int
     */
    const _MONTHS_PER_YEAR = 12;

    /**
     * @var int
     */
    const _MONTHS_PER_QUARTER = 3;

    /**
     * @var int
     */
    const _WEEKS_PER_YEAR = 52;

    /**
     * @var int
     */
    const _WEEKS_PER_MONTH = 4;

    /**
     * @var int
     */
    const _DAYS_PER_WEEK = 7;

    /**
     * @var int
     */
    const _HOURS_PER_DAY = 24;

    /**
     * @var int
     */
    const _MINUTES_PER_HOUR = 60;

    /**
     * @var int
     */
    const _SECONDS_PER_MINUTE = 60;

    /**
     * Creates a new Date time immutable instance.
     *
     * @param mixed ...$args List of optional argument to support future changes of the immatable class
     *
     * @return \DateTimeImmutable
     */
    public static function create(string $datetime = 'now', \DateTimeZone $tz = null, ...$args)
    {
        return new \DateTimeImmutable($datetime, $tz, ...$args);
    }

    /**
     * Creates date time immutable from provided timestamp.
     *
     * @param \DateTimeZone $tz
     *
     * @return \DateTimeImmutable
     */
    public static function timestamp(int $timestamp, \DateTimeZone $tz = null)
    {
        return new \DateTimeImmutable('@'.$timestamp, $tz);
    }

    /**
     * Returns the current date-time based on user provided timezone.
     *
     * @param \DateTimeZone|string timezone
     *
     * @return \DateTimeInterface
     */
    public static function now($tz = null)
    {
        return new \DateTimeImmutable('now', \is_string($tz) ? new \DateTimeZone($tz) : $tz);
    }

    /**
     * Checks if a given date time is a future date time.
     *
     * @return bool
     */
    public static function isfuture(\DateTimeInterface $now)
    {
        return self::gt($now, self::nowTz());
    }

    /**
     * Check if the instance is in the past, ie. less (before) than now.
     *
     * @return bool
     */
    public static function ispast(\DateTimeInterface $current_date)
    {
        return self::lt($current_date, self::nowTz());
    }

    /**
     * Get the timezone of a dateTime instance.
     *
     * @return \DateTimeZone
     */
    public static function getTz(\DateTimeInterface $value)
    {
        return $value->getTimeZone();
    }

    /**
     * Return the current dateTime value alongs with the timezone.
     *
     * @return \DateTimeInterface
     */
    public static function nowTz()
    {
        return self::now(self::getTz(new \DateTimeImmutable()));
    }

    /**
     * Check if fisrt date is greater than other date.
     *
     * @param \DateTimeInterface $date
     * @param \DateTimeInterface $other
     *
     * @return bool
     */
    public static function gt($date, $other)
    {
        return $date > $other;
    }

    /**
     * Check if fisrt date is greater than other date.
     *
     * @param \DateTimeInterface $date
     * @param \DateTimeInterface $other
     *
     * @return bool
     */
    public static function lt($date, $other)
    {
        return $date < $other;
    }

    // #region Arithmetic operation on Date Time Immutable instance
    /**
     * Add time in days to date instance.
     *
     * @param \DateTimeInterface|string|int|null $date
     * @param int                                $days
     *
     * @return \DateTimeInterface
     */
    public static function addDays($date, $days = 0)
    {
        return self::addSecs($date, $days * self::_HOURS_PER_DAY * self::_MINUTES_PER_HOUR * self::_SECONDS_PER_MINUTE);
    }

    /**
     * Add time in hours to date instance.
     *
     * @param \DateTimeInterface|string|int|null $date
     *
     * @return \DateTimeInterface
     */
    public static function addHrs($date, $hours = 0)
    {
        return self::addSecs($date, $hours * self::_MINUTES_PER_HOUR * self::_SECONDS_PER_MINUTE);
    }

    /**
     * Add time in minutes to date instance.
     *
     * @param \DateTimeInterface|string|int|null $date
     * @param int                                $minutes
     *
     * @return \DateTimeInterface
     */
    public static function addMinutes($date, $minutes = 0)
    {
        return self::addSecs($date, $minutes * self::_SECONDS_PER_MINUTE);
    }

    /**
     * Add time in seconds to date instance.
     *
     * @param \DateTimeInterface|string|int|null $date
     *
     * @return \DateTimeInterface
     */
    public static function addSecs($date = 'now', $seconds = 0)
    {
        return self::make($date)->modify((int) $seconds.' second');
    }

    /**
     * Add date interval to provided date instance.
     *
     * @param \DateTimeInterface|string|int|null $date
     *
     * @return \DateTimeImmutable
     */
    public static function add($date, \DateInterval $interval)
    {
        return self::make($date)->add($interval);
    }

    /**
     * Substract time in days from date instance.
     *
     * @param \DateTimeInterface|string|int|null $date
     * @param int                                $days
     *
     * @return \DateTimeInterface
     */
    public static function subDays($date, $days = 0)
    {
        return self::subSecs($date, $days * self::_HOURS_PER_DAY * self::_MINUTES_PER_HOUR * self::_SECONDS_PER_MINUTE);
    }

    /**
     * Substract time in hours from date instance.
     *
     * @param \DateTimeInterface|string|int|null $date
     * @param int                                $hours
     *
     * @return \DateTimeInterface
     */
    public static function subHrs($date, $hours = 0)
    {
        return self::subSecs($date, $hours * self::_MINUTES_PER_HOUR * self::_SECONDS_PER_MINUTE);
    }

    /**
     * Substract time in minutes from date instance.
     *
     * @param \DateTimeInterface|string|int|null $date
     * @param int                                $minutes
     *
     * @return \DateTimeInterface
     */
    public static function subMinutes($date, $minutes = 0)
    {
        return self::subSecs($date, $minutes * self::_SECONDS_PER_MINUTE);
    }

    /**
     * Substract time in seconds from date instance.
     *
     * @param \DateTimeInterface|string|int|null $date
     * @param int                                $seconds
     *
     * @return \DateTimeInterface
     */
    public static function subSecs($date = 'now', $seconds = 0)
    {
        return self::make($date)->modify(sprintf('-%d second', (int) $seconds));
    }

    /**
     * Substract date interval from provided date instance.
     *
     * @param \DateTimeInterface|string|int|null $date
     *
     * @return \DateTimeImmutable
     */
    public static function sub(\DateTimeInterface $date, \DateInterval $interval)
    {
        return self::make($date)->sub($interval);
    }
    // #region Arithmetic operations on date time immutable instances

    /**
     * Returns the min of 2 date instances.
     *
     * @param \DateTimeInterface|string|null $other
     *
     * @throws \InvalidArgumentException
     *
     * @return \DateTimeInterface
     */
    public static function min(\DateTimeInterface $date, $other = null)
    {
        return self::lt($date, $other = self::resolve($date, $other)) ? $date : $other;
    }

    /**
     * Returns the max of 2 date instances.
     *
     * @param \DateTimeInterface|string|null $other
     *
     * @throws \InvalidArgumentException
     *
     * @return \DateTimeInterface
     */
    public static function max(\DateTimeInterface $date, $other = null)
    {
        return self::gt($date, $other = self::resolve($date, $other)) ? $date : $other;
    }

    /**
     * Compares the formatted values of the two dates.
     *
     * @param \DateTimeInterface      $date
     * @param \DateTimeInterface|null $other  the instance to compare with or null to use current day
     * @param string                  $format the date formats to compare
     *
     * @throws \InvalidArgumentException
     *
     * @return bool
     */
    public static function same($date, $other = null, $format = 'c')
    {
        if (!isset($other)) {
            $other = self::nowTz();
        }
        self::assert($other, \gettype(null));

        return $date->format($format) === $other->format($format);
    }

    /**
     * Return the DateTime instance passed through, a now instance in the same timezone
     * if null given or parse the input if string given.
     *
     * @param \DateTimeInterface|string|null $date
     *
     * @return \DateTimeInterface
     */
    public static function resolve(\DateTimeInterface $from, $date = null)
    {
        if (null === $date) {
            return self::nowTz();
        }
        if (\is_string($date)) {
            return self::create($date, $from->getTimezone());
        }

        return self::make($date, $from->getTimezone());
    }

    /**
     * Create a date time immutable from a date time like instance.
     *
     * @param \DateTimeInterface|string|int|null $date
     *
     * @throws \InvalidArgumentException
     *
     * @return \DateTimeImmutable
     */
    public static function make($date = 'now', \DateTimeZone $tz = null)
    {
        if ($date instanceof \DateTimeInterface) {
            return self::timestamp($date->getTimestamp(), $tz ?? ($tz = $date->getTimezone() ? $tz : null));
        }
        if (\is_int($date)) {
            return self::timestamp($date, $tz);
        }
        self::assert($date, ['string', \gettype(null)]);

        return self::create($date ?? 'now', $tz);
    }

    /**
     * Compute years difference btween 2 date.
     *
     * @param \DateTimeInterface|string|null $other
     * @param bool                           $exact Get the exact of the difference
     *
     * @return float
     */
    public static function yrsDiff(\DateTimeInterface $date, $other = null, $exact = true)
    {
        return self::weeksDiff($date, $other, $exact) / self::_WEEKS_PER_YEAR;
    }

    /**
     * Compute weeks difference btween 2 date.
     *
     * @param \DateTimeInterface|string|null $other
     * @param bool                           $exact Get the exact of the difference
     *
     * @return float
     */
    public static function weeksDiff(\DateTimeInterface $date, $other = null, $exact = true)
    {
        return self::daysDiff($date, $other, $exact) / self::_DAYS_PER_WEEK;
    }

    /**
     * Compute days difference btween 2 date.
     *
     * @param \DateTimeInterface|string|null $other
     * @param bool                           $exact Get the exact of the difference
     *
     * @return float
     */
    public static function daysDiff(\DateTimeInterface $date, $other = null, $exact = true)
    {
        return self::hrsDiff($date, $other, $exact) / self::_HOURS_PER_DAY;
    }

    /**
     * Compute hours difference btween 2 date.
     *
     * @param \DateTimeInterface|string|null $other
     * @param bool                           $exact Get the exact of the difference
     *
     * @return float
     */
    public static function hrsDiff(\DateTimeInterface $date, $other = null, $exact = true)
    {
        return self::secsDiff($date, $other, $exact) / self::_SECONDS_PER_MINUTE / self::_MINUTES_PER_HOUR;
    }

    /**
     * Get the difference in minutes.
     *
     * @param \DateTimeInterface|string|null $date
     * @param bool                           $exact Get the exact of the difference
     *
     * @return float
     */
    public static function minDiff(\DateTimeInterface $source, $date = null, $exact = true)
    {
        return self::secsDiff($source, $date, $exact) / self::_SECONDS_PER_MINUTE;
    }

    /**
     * Compute the difference in seconds between 2 dates.
     *
     * @param \DateTimeInterface|string|null $date
     * @param bool                           $exact Get the exact of the difference
     *
     * @return int
     */
    public static function secsDiff(\DateTimeInterface $source, $date = null, $exact = true)
    {
        $diff = $source->diff(self::resolve($source, $date));
        $value = $diff->days * self::_HOURS_PER_DAY * self::_MINUTES_PER_HOUR * self::_SECONDS_PER_MINUTE + $diff->h * self::_MINUTES_PER_HOUR * self::_SECONDS_PER_MINUTE + $diff->i * self::_SECONDS_PER_MINUTE + $diff->s;

        return $exact || !$diff->invert ? $value : -$value;
    }

    /**
     * Throws an exception if the given object is not a DateTime and does not implement DateTimeInterface
     * and not in $other.
     *
     * @param mixed $date
     * @param array $types
     *
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    public static function assert($date, $types = [])
    {
        if (($date instanceof \DateTimeInterface)) {
            return;
        }
        if (\in_array(\gettype($date), $types, true)) {
            return;
        }
        $message = 'Expected type : ';
        foreach ((array) $types as $expect) {
            $message .= "{$expect}, ";
        }
        throw new \InvalidArgumentException($message.'\DateTimeImmutable, DateTime, DateTimeInterface, '.(\is_object($date) ? \get_class($date) : \gettype($date)).' given');
    }
}
