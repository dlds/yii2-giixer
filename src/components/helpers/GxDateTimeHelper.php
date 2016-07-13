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
    const UNIX_HOUR = 3600;
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
     * Retrieves midnights count between two timestamps
     * @param int $since timestmap 1
     * @param int $to timestmap 2
     */
    public static function getMidnightsCount($since, $to)
    {
        $date1 = new \DateTime(date('Y-m-d', $since));
        $date2 = new \DateTime(date('Y-m-d', $to));

        return $date1->diff($date2)->days;
    }

    /**
     * Retrieves timestamp bounds
     * @param type $timestamp
     * @param type $interval
     * @return type
     */
    public static function getBounds($timestamp, $interval = self::UNIX_DAY, $timezone = 'Europe/Prague')
    {
        $dtNow = new \DateTime();

        // Set a non-default timezone if needed
        $dtNow->setTimezone(new \DateTimeZone($timezone));

        $dtNow->setTimestamp($timestamp);

        $rules = static::getBoundsRules($interval);

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
    private static function getBoundsRules($interval)
    {
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
