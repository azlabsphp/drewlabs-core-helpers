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

/**
 * @deprecated Use \Drewlabs\Core\Helpers\DateTime instead
 */
final class ImmutableDateTime
{
    /**
     * Creates a new Date time immutable instance.
     *
     * @param mixed ...$args List of optional argument to support future changes of the immatable class
     *
     * @return \DateTimeImmutable
     */
    public static function create(string $datetime = 'now', ?\DateTimeZone $tz = null, ...$args)
    {
        return new \DateTimeImmutable($datetime, $tz, ...$args);
    }

    /**
     * Creates date time immutable from provided timestamp.
     *
     * @return \DateTimeImmutable
     */
    public static function timestamp(int $timestamp, ?\DateTimeZone $tz = null)
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
        return DateTime::gt($now, DateTime::nowTz());
    }

    /**
     * Check if the instance is in the past, ie. less (before) than now.
     *
     * @return bool
     */
    public static function ispast(\DateTimeInterface $current_date)
    {
        return DateTime::lt($current_date, DateTime::nowTz());
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
        return DateTime::now(DateTime::getTz(new \DateTimeImmutable()));
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
        return DateTime::addSecs($date, $days * DateTime::_HOURS_PER_DAY * DateTime::_MINUTES_PER_HOUR * DateTime::_SECONDS_PER_MINUTE);
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
        return DateTime::addSecs($date, $hours * DateTime::_MINUTES_PER_HOUR * DateTime::_SECONDS_PER_MINUTE);
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
        return DateTime::addSecs($date, $minutes * DateTime::_SECONDS_PER_MINUTE);
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
        return DateTime::make($date)->modify((int) $seconds.' second');
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
        return DateTime::make($date)->add($interval);
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
        return DateTime::subSecs($date, $days * DateTime::_HOURS_PER_DAY * DateTime::_MINUTES_PER_HOUR * DateTime::_SECONDS_PER_MINUTE);
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
        return DateTime::subSecs($date, $hours * DateTime::_MINUTES_PER_HOUR * DateTime::_SECONDS_PER_MINUTE);
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
        return DateTime::subSecs($date, $minutes * DateTime::_SECONDS_PER_MINUTE);
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
        return DateTime::make($date)->modify(sprintf('-%d second', (int) $seconds));
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
        return DateTime::make($date)->sub($interval);
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
        return DateTime::lt($date, $other = DateTime::resolve($date, $other)) ? $date : $other;
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
        return DateTime::gt($date, $other = DateTime::resolve($date, $other)) ? $date : $other;
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
            $other = DateTime::nowTz();
        }
        DateTime::assert($other, \gettype(null));

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
            return DateTime::nowTz();
        }
        if (\is_string($date)) {
            return DateTime::create($date, $from->getTimezone());
        }

        return DateTime::make($date, $from->getTimezone());
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
    public static function make($date = 'now', ?\DateTimeZone $tz = null)
    {
        if ($date instanceof \DateTimeInterface) {
            return DateTime::timestamp($date->getTimestamp(), $tz ?? ($tz = $date->getTimezone() ? $tz : null));
        }
        if (\is_int($date)) {
            return DateTime::timestamp($date, $tz);
        }
        DateTime::assert($date, ['string', \gettype(null)]);

        return DateTime::create($date ?? 'now', $tz);
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
        return DateTime::weeksDiff($date, $other, $exact) / DateTime::_WEEKS_PER_YEAR;
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
        return DateTime::daysDiff($date, $other, $exact) / DateTime::_DAYS_PER_WEEK;
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
        return DateTime::hrsDiff($date, $other, $exact) / DateTime::_HOURS_PER_DAY;
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
        return DateTime::secsDiff($date, $other, $exact) / DateTime::_SECONDS_PER_MINUTE / DateTime::_MINUTES_PER_HOUR;
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
        return DateTime::secsDiff($source, $date, $exact) / DateTime::_SECONDS_PER_MINUTE;
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
        $diff = $source->diff(DateTime::resolve($source, $date));
        $value = $diff->days * DateTime::_HOURS_PER_DAY * DateTime::_MINUTES_PER_HOUR * DateTime::_SECONDS_PER_MINUTE + $diff->h * DateTime::_MINUTES_PER_HOUR * DateTime::_SECONDS_PER_MINUTE + $diff->i * DateTime::_SECONDS_PER_MINUTE + $diff->s;

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
        if ($date instanceof \DateTimeInterface) {
            return;
        }
        if (\in_array(\gettype($date), $types, true)) {
            return;
        }
        $message = 'Expected type : ';
        foreach ((array) $types as $expect) {
            $message .= "{$expect}, ";
        }
        throw new \InvalidArgumentException($message.'\DateTimeImmutable, DateTime, DateTimeInterface, '.(\is_object($date) ? $date::class : \gettype($date)).' given');
    }
}
