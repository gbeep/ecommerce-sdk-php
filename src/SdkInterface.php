<?php

namespace Gobeep\Ecommerce;

/**
 * SDK interface.
 */
interface SdkInterface
{
    /**
     * Statuses
     */
    public const STATUS_PENDING = 'pending';
    public const STATUS_REFUNDED = 'refunded';

    /**
     * Environments
     */
    public const ENV_STAGING = 'canary';
    public const ENV_PRODUCTION = 'stable';

    /**
     * Regions
     */
    public const REGION_EU = 'eu';
    public const REGION_AM = 'am';

    /**
     * Sets secret
     *
     * @param string $secret Secret
     */
    public function setSecret(string $secret): Sdk;

    /**
     * Sets campaign ID
     *
     * @param string $id Campaign ID
     */
    public function setCampaignId(string $id): Sdk;

    /**
     * Sets cashier ID
     *
     * @param string $id Cashier ID
     */
    public function setCashierId(string $id): Sdk;

    /**
     * Sets environment
     *
     * @param string $env Environment
     */
    public function setEnvironment(string $env = self::ENV_STAGING): Sdk;

    /**
     * Sets region
     *
     * @param string $region Region
     */
    public function setRegion(string $region = self::REGION_EU): Sdk;

    /**
     * Sets timezone
     *
     * @param string $timezone Timezone
     */
    public function setTimezone(string $timezone): Sdk;

    /**
     * Signs a payload with hash_hmac (SHA256)
     *
     * @param string $payload Payload to sign
     *
     * @return string
     */
    public function sign(string $payload): string;

    /**
     * Returns campaign link
     */
    public function getCampaignLink(): string;

    /**
     * Returns cashier link
     */
    public function getCashierLink(array $payload): string;

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
    public function isDateInRange(string $date, string $startDate, string $endDate): bool;

    /**
     * Checks if date passed to method is a day eligible to operation
     *
     * @param string $date         Date to compare
     * @param array  $eligibleDays Eligible Days
     *
     * @return bool
     */
    public function isDayEligible(string $date, array $eligibleDays): bool;
}
