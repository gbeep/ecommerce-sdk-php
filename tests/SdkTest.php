<?php

declare(strict_types=1);

namespace Gobeep\Ecommerce\Test;

use Gobeep\Ecommerce\Sdk;
use Gobeep\Ecommerce\SdkInterface;
use PHPUnit\Framework\TestCase;

class SdkTest extends TestCase
{
    /**
     * Campaign ID
     *
     * @var string
     */
    protected $campaignId;

    /**
     * Cashier ID
     *
     * @var int
     */
    protected $cashierId;

    /**
     * Secret
     *
     * @var string
     */
    protected $secret;

    /**
     * Region
     *
     * @var string
     */
    protected $region;

    /**
     * Environment
     *
     * @var string
     */
    protected $environment;

    /**
     * Timezone
     *
     * @var string
     */
    protected $timezone;

    /**
     * Date used for testing
     *
     * @var string
     */
    protected $date;

    protected function setUp(): void
    {
        $this->campaignId = 'tYp4G';
        $this->cashierId = 81235;
        $this->secret = '46b2063c2e23cc01aec102a1b0084c70';
        $this->region = SdkInterface::REGION_EU;
        $this->environment = SdkInterface::ENV_PRODUCTION;
        $this->timezone = 'Europe/Paris';
        // Tuesday, May 19th 2020
        $this->date = date('Y-m-d H:i:s', 1589884491);
    }

    public function testGetCampaignLink(): void
    {
        $sdk = new Sdk();
        $sdk->setCampaignId($this->campaignId)
            ->setEnvironment($this->environment)
            ->setRegion($this->region);

        $this->assertSame('https://eu.gb.run/tYp4G/online', $sdk->getCampaignLink());
    }

    public function testCashierLink(): void
    {
        $sdk = new Sdk();
        $sdk->setCashierId($this->cashierId)
            ->setSecret($this->secret)
            ->setEnvironment($this->environment)
            ->setRegion($this->region);

        $this->assertSame(
            'https://eu-epos.gb.run/81235?order_amount=10.55&order_id=123&signature=D9gIj1bETVzDw1rZOgw6pvpdHBcJxgQoZgNjW2JVWeo%3D',
            $sdk->getCashierLink(['order_amount' => 10.55, 'order_id' => '123'])
        );
    }

    public function testIsCurrentDateInRange(): void
    {
        $sdk = new Sdk();
        $sdk->setTimezone('Europe/Paris');
        $this->assertTrue($sdk->isDateInRange(
            $this->date,
            date('Y-m-d', strtotime('May 18 2020')),
            date('Y-m-d', strtotime('May 20 2020'))
        ));
    }

    public function testIsDayEligible(): void
    {
        $sdk = new Sdk();
        $sdk->setTimezone('Europe/Paris');
        $this->assertTrue($sdk->isDayEligible($this->date, [0, 1, 2]));
    }
}
