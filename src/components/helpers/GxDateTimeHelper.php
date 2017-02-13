<?php

/**
 * @link http://www.digitaldeals.cz/
 * @copyright Copyright (c) 2016 Digital Deals s.r.o.
 * @license http://www.digitaldeals.cz/license/
 * @author Jiri Svoboda <jiri.svoboda@dlds.cz>
 */

namespace dlds\giixer\components\helpers;

use yii\helpers\ArrayHelper;

/**
 * This is helper class for manipulating with dates and time
 * ---
 * It is all based on unix time and timestamps
 * @see \DateTime
 * @see \DateTimeZone
 */
class GxDateTimeHelper
{

    /**
     * UNIX
     */
    const UNIX_MIN = 60;
    const UNIX_HOUR = self::UNIX_MIN * 60;
    const UNIX_DAY = self::UNIX_HOUR * 24;
    const UNIX_WEEK = self::UNIX_DAY * 7;
    const UNIX_MONTH = self::UNIX_DAY * 30;
    const UNIX_YEAR = self::UNIX_DAY * 365;

    /**
     * Bounds
     */
    const BOUND_MIN = 'min';
    const BOUND_MAX = 'max';

    /**
     * Formats
     */
    const FORMAT_DAY_SIMPLE_MONTH_TEXT = 'php:j. F';
    const FORMAT_DAY_MONTH = 'php:d.m';
    const FORMAT_DAY_MONTH_YEAR = 'php:d.m.Y';

    /**
     * Retrieves years in unix format
     * @param int $count
     * @return int
     */
    public static function years($count)
    {
        return $count * self::UNIX_YEAR;
    }

    /**
     * Retrieves months in unix format
     * @param int $count
     * @return int
     */
    public static function months($count)
    {
        return $count * self::UNIX_MONTH;
    }

    /**
     * Retrieves weeks in unix format
     * @param int $count
     * @return int
     */
    public static function weeks($count)
    {
        return $count * self::UNIX_WEEK;
    }

    /**
     * Retrieves days in unix format
     * @param int $count
     * @return int
     */
    public static function days($count)
    {
        return $count * self::UNIX_DAY;
    }

    /**
     * Retrieves hours in unix format
     * @param int $count
     * @return int
     */
    public static function hours($count)
    {
        return $count * self::UNIX_HOUR;
    }

    /**
     * Retrieves mins in unix format
     * @param int $count
     * @return int
     */
    public static function mins($count)
    {
        return $count * self::UNIX_MIN;
    }

    /**
     * Retrieves years count based on given timestamp
     * @param int $ts
     * @return round(int
     */
    public static function countYears($ts)
    {
        return round($ts / self::UNIX_YEAR);
    }

    /**
     * Retrieves months count based on given timestamp
     * @param int $ts
     * @return round(int
     */
    public static function countMonths($ts)
    {
        return round($ts / self::UNIX_MONTH);
    }

    /**
     * Retrieves weeks count based on given timestamp
     * @param int $ts
     * @return round(int
     */
    public static function countWeeks($ts)
    {
        return round($ts / self::UNIX_WEEK);
    }

    /**
     * Retrieves days count based on given timestamp
     * @param int $ts
     * @return round(int
     */
    public static function countDays($ts)
    {
        return round($ts / self::UNIX_DAY);
    }

    /**
     * Retrieves hours count based on given timestamp
     * @param int $ts
     * @return round(int
     */
    public static function countHours($ts)
    {
        return round($ts / self::UNIX_HOUR);
    }

    /**
     * Retrieves mins count based on given timestamp
     * @param int $ts
     * @return round(int
     */
    public static function countMins($ts)
    {
        return round($ts / self::UNIX_MIN);
    }

    /**
     * Retrieves midnights count between two timestamps
     * @param int $since timestmap 1
     * @param int $to timestmap 2
     */
    public static function midnights($since, $to)
    {
        $date1 = new \DateTime(date('Y-m-d', $since));
        $date2 = new \DateTime(date('Y-m-d', $to));

        return $date1->diff($date2)->days;
    }

    /**
     * Indicates if given born timestampe is in age or older then given age
     * @param int $age
     * @param int $born
     * @param int|boolean $now
     * @return boolean
     */
    public static function reachAge($age, $born, $now = false)
    {
        if (false === $now) {
            $now = time();
        }

        return $now - $born >= $age;
    }

    /**
     * Retrieves timestamp bounds
     * @param type $timestamp
     * @param type $interval
     * @return type
     */
    public static function bounds($timestamp, $interval = self::UNIX_DAY, $timezone = 'Europe/Prague')
    {
        $dtNow = new \DateTime();

        // Set a non-default timezone if needed
        $dtNow->setTimezone(new \DateTimeZone($timezone));

        $dtNow->setTimestamp($timestamp);

        $rules = static::boundsRules($interval);

        // create begin bound from current time
        $begin = clone $dtNow;

        // apply begin (min) bound rules if exist
        if (isset($rules[self::BOUND_MIN])) {
            foreach ($rules[self::BOUND_MIN] as $rule) {
                $begin->modify($rule);
            }
        }

        // create end bound from begin bound
        $end = clone $begin;

        // apply end (max) bound rules if exist
        if (isset($rules[self::BOUND_MAX])) {
            foreach ($rules[self::BOUND_MAX] as $rule) {
                $end->modify($rule);
            }
        }

        // return bounds
        return [
            self::BOUND_MIN => $begin->getTimestamp(),
            self::BOUND_MAX => $end->getTimestamp(),
        ];
    }

    /**
     * Retrieves bounds modify rules
     * @param int $interval given time interval
     * @return array rules
     */
    private static function boundsRules($interval)
    {
        switch ($interval) {
            case self::UNIX_WEEK:
                return [
                    self::BOUND_MIN => [
                        // Go to midnight
                        'monday this week'
                    ],
                    self::BOUND_MAX => [
                        // Go to tommorow
                        'next monday midnight',
                        // Adjust from the next day to the end of the day, per original question
                        '1 second ago',
                    ],
                ];
            case self::UNIX_MONTH:
                return [
                    self::BOUND_MIN => [
                        // Go to first day of current month
                        'first day of this month midnight',
                    ],
                    self::BOUND_MAX => [
                        // Go to last day of current month
                        'first day of next month midnight',
                        // Adjust from the next day to the end of the day, per original question
                        '1 second ago',
                    ],
                ];
            case self::UNIX_YEAR:
                return [
                    self::BOUND_MIN => [
                        // Go to first day of this year
                        'first day of January',
                        // Go to midnight
                        'midnight',
                    ],
                    self::BOUND_MAX => [
                        // Go to last day of this year
                        'last day of December',
                        // Go to next day
                        'next day',
                        // Adjust from the next day to the end of the day, per original question
                        '1 second ago',
                    ],
                ];
        }

        // default for UNIX_DAY interval
        return [
            self::BOUND_MIN => [
                // Go to midnight
                'today'
            ],
            self::BOUND_MAX => [
                // Go to tommorow
                'tomorrow',
                // Adjust from the next day to the end of the day, per original question
                '1 second ago',
            ],
        ];
    }

}
