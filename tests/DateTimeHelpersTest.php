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

namespace Drewlabs\Core\Helpers\Tests;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class DateTimeHelpersTest extends TestCase
{
    public function testNowDatetimeFunction()
    {
        $date = drewlabs_core_datetime_now('GMT');
        $this->assertInstanceOf(\DateTimeInterface::class, $date);
        $this->assertSame($date->getTimezone()->getName(), 'GMT', 'Expect the timezone of the created date to be UTC');
    }

    public function testDateTimeIsFuture()
    {
        $this->assertTrue(drewlabs_core_datetime_is_future(new DateTimeImmutable('2021-10-02T09:19:01.012345Z')), 'Expect the provided date to be a future date');
    }

    public function testDateTimeIsPast()
    {
        $this->assertTrue(drewlabs_core_datetime_is_past(new DateTimeImmutable('2020-10-02T09:18:01.012345Z')), 'Expect the provided date to be a past date');
    }

    public function testDateTimeFromTimeStampFunction()
    {
        $date = drewlabs_core_datetime_now_with_tz();
        $timestamps = $date->getTimestamp();
        $new_date = drewlabs_core_datetime_from_timestamp($timestamps);
        $this->assertInstanceOf(\DateTimeInterface::class, $date, 'Expect $date to be an instance DateTimeInterface');
        $this->assertInstanceOf(\DateTimeInterface::class, $new_date, 'Expect $date to be an instance DateTimeInterface');
        $this->assertTrue(drewlabs_core_datetime_is_same($date, $new_date), 'Expects $date and $new_date variables to be the same');
    }

    public function testExpectDateTimeFunction()
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->expectException(\InvalidArgumentException::class, 'Expect the function call to throw an exception');
        drewlabs_core_datetime_expect_date_time(null);
    }

    public function testMakeDateFunction()
    {
        $this->assertInstanceOf(\DateTimeInterface::class, drewlabs_core_datetime_make_date(new DateTimeImmutable()), 'Expect the result of the make function to return a date time instance');
    }

    public function testAddMinutesFunction()
    {
        $date = drewlabs_core_datetime_now_with_tz();
        $minutes = (int) $date->format('i');
        $this->assertSame((int) (drewlabs_core_datetime_add_minutes($date, 5)->format('i')), ($minutes + 5 > 60 ? ($minutes + 5) % 60 : $minutes + 5), 'Expect the returned number of minutes to be equals to the initial date number of minutes + 5');
    }

    public function testDateTimeMaxFunction()
    {
        $first_date = drewlabs_core_datetime_now_with_tz();
        $second_date = drewlabs_core_datetime_add_minutes(drewlabs_core_datetime_now_with_tz(), 10);
        $this->assertTrue(drewlabs_core_datetime_is_same(drewlabs_core_datetime_max($first_date, $second_date), $second_date), 'Expects second date to be the result of the max function');
    }

    public function testDateTimeDiffInHoursFunction()
    {
        $first_date = drewlabs_core_datetime_now_with_tz();
        $second_date = drewlabs_core_datetime_add_minutes(drewlabs_core_datetime_now_with_tz(), 120);
        $hrs_diff = drewlabs_core_datetime_hrs_diff($first_date, $second_date);
        $this->assertSame($hrs_diff, 2, 'Expect the difference in hours to equals 0');
    }

    public function testDateTimeDiffInMinutesFunction()
    {
        $first_date = drewlabs_core_datetime_now_with_tz();
        $second_date = drewlabs_core_datetime_add_minutes(drewlabs_core_datetime_now_with_tz(), 10);
        $min_diff = drewlabs_core_datetime_min_diff($first_date, $second_date);
        $this->assertSame($min_diff, 10, 'Expect the difference in hours to equals 0');
    }
}
