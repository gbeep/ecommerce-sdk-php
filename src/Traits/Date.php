<?php

/**
 * This file is part of the gbeep/ecommerce-sdk-php library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @copyright Copyright (c) Gobeep <tech@gobeep.co>
 * @license http://opensource.org/licenses/MIT MIT
 */

declare(strict_types=1);

namespace Gobeep\Ecommerce\Traits;

use Gobeep\Ecommerce\Exceptions\ValidationException;

trait Date
{
    /**
     * Timezone
     *
     * @var string
     */
    protected $timezone;

    /**
     * Sets timezone
     *
     * @param string $timezone Timezone
     */
    public function setTimezone(string $timezone): self
    {
        $this->timezone = $timezone;

        return $this;
    }

    /**
     * Checks if first date passed as first argument is in the range of
     * start date/end date passed to second and third argument
     *
     * @param string $date      Date to compare
     * @param string $startDate Start Date
     * @param string $endDate   End Date
     *
     * @return bool
     */
    public function isDateInRange(string $date, string $startDate, string $endDate): bool
    {
        $this->validateTimezone();

        // If start date is empty, set it far in the past
        if (empty($startDate)) {
            $startDate = '1970-01-01';
        }
        // If end date is empty, set it far in the future
        if (empty($endDate)) {
            $endDate = date('Y-m-d', strtotime('+10 years'));
        }

        // Convert to timestamp
        $startDate = new \DateTime($startDate . ' 00:00:00', new \DateTimeZone($this->timezone));
        $endDate = new \DateTime($endDate . ' 23:59:59', new \DateTimeZone($this->timezone));
        $currentDate = new \DateTime($date, new \DateTimeZone($this->timezone));

        // Convert to UTC timezones
        $startDate->setTimezone(new \DateTimeZone('UTC'));
        $endDate->setTimezone(new \DateTimeZone('UTC'));
        $currentDate->setTimezone(new \DateTimeZone('UTC'));

        // Convert to timestamp representation
        $startDateTs = $startDate->format('U');
        $endDateTs = $endDate->format('U');
        $currentDateTs = $currentDate->format('U');

        // Check that user date is between start & end, exit if not in range
        return (($currentDateTs >= $startDateTs) && ($currentDateTs <= $endDateTs));
    }

    /**
     * Checks if date passed to method is a day eligible to operation
     *
     * @param string $date         Date to compare
     * @param array  $eligibleDays Eligible Days
     *
     * @return bool
     */
    public function isDayEligible(string $date, array $eligibleDays): bool
    {
        $this->validateTimezone();

        $currentDate = new \DateTime($date, new \DateTimeZone($this->timezone));
        // If date is in range, check if day of week is eligible
        $dayOfWeek = intval($currentDate->format('w'));

        return in_array($dayOfWeek, $eligibleDays);
    }

    /**
     * Validates timezone
     *
     * @throws ValidationException
     */
    protected function validateTimezone(): void
    {
        if (!in_array($this->timezone, timezone_identifiers_list())) {
            throw new ValidationException("Timezone is not valid");
        }
    }
}
