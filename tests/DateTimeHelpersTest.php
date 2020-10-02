<?php

namespace Drewlabs\Core\Helpers\Tests;

use PHPUnit\Framework\TestCase;

class DateTimeHelpersTest extends TestCase
{
    public function testNowDatetimeFunction()
    {
        $date = \drewlabs_core_datetime_now('GMT');
        $this->assertInstanceOf(\DateTime::class, $date);
        $this->assertEquals($date->getTimezone()->getName(), 'GMT', 'Expect the timezone of the created date to be UTC');
    }

    public function testDateTimeIsFuture()
    {
        $this->assertTrue(\drewlabs_core_datetime_is_future(new \DateTime('2021-10-02T09:19:01.012345Z')), 'Expect the provided date to be a future date');
    }

    public function testDateTimeIsPast()
    {
        $this->assertTrue(\drewlabs_core_datetime_is_past(new \DateTime('2020-10-02T09:18:01.012345Z')), 'Expect the provided date to be a past date');
    }

    public function testDateTimeFromTimeStampFunction()
    {
        $date = \drewlabs_core_datetime_now_with_tz();
        $timestamps = $date->getTimestamp();
        $new_date = \drewlabs_core_datetime_from_timestamp($timestamps);
        $this->assertInstanceOf(\DateTime::class, $date, 'Expect $date to be an instance of DateTime class');
        $this->assertInstanceOf(\DateTime::class, $new_date, 'Expect $date to be an instance of DateTime class');
        $this->assertTrue(\drewlabs_core_datetime_is_same($date, $new_date), 'Expects $date and $new_date variables to be the same');
    }
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testExpectDateTimeFunction()
    {
        $this->expectException(\InvalidArgumentException::class, 'Expect the function call to throw an exception');
        \drewlabs_core_datetime_expect_date_time(null);
    }

    public function testMakeDateFunction()
    {
        $this->assertInstanceOf(\DateTime::class, \drewlabs_core_datetime_make_date(new \DateTime), 'Expect the result of the make function to return a date time instance');
    }

    public function testAddMinutesFunction()
    {
        $date = \drewlabs_core_datetime_now_with_tz(); //
        $minutes = (int) $date->format('i');
        $this->assertEquals((int)(\drewlabs_core_datetime_add_minutes($date, 5)->format('i')), ($minutes + 5 > 60 ? ($minutes + 5) % 60 : $minutes + 5), 'Expect the returned number of minutes to be equals to the initial date number of minutes + 5');
    }

    public function testDateTimeMaxFunction()
    {
        $first_date = drewlabs_core_datetime_now_with_tz();
        $second_date = \drewlabs_core_datetime_add_minutes(\drewlabs_core_datetime_now_with_tz(), 10);
        $this->assertTrue(\drewlabs_core_datetime_is_same(\drewlabs_core_datetime_max($first_date, $second_date), $second_date), 'Expects second date to be the result of the max function');
    }

    public function testDateTimeDiffInHoursFunction()
    {
        $first_date = drewlabs_core_datetime_now_with_tz();
        $second_date = \drewlabs_core_datetime_add_minutes(\drewlabs_core_datetime_now_with_tz(), 120);
        $hrs_diff = \drewlabs_core_datetime_hrs_diff($first_date, $second_date);
        $this->assertEquals($hrs_diff, 2, 'Expect the difference in hours to equals 0');
    }

    public function testDateTimeDiffInMinutesFunction()
    {
        $first_date = drewlabs_core_datetime_now_with_tz();
        $second_date = \drewlabs_core_datetime_add_minutes(\drewlabs_core_datetime_now_with_tz(), 10);
        $min_diff = \drewlabs_core_datetime_min_diff($first_date, $second_date);
        $this->assertEquals($min_diff, 10, 'Expect the difference in hours to equals 0');
    }
}
