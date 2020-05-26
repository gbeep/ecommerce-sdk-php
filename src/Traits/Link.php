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

use Gobeep\Ecommerce\SdkInterface;
use Gobeep\Ecommerce\Exceptions\ValidationException;

trait Link
{
    /**
     * Campaign ID: used for building game URL
     *
     * @var string
     */
    protected $campaignId = '';

    /**
     * Cashier ID: used for building cashier URL
     *
     * @var string
     */
    protected $cashierId = '';

    /**
     * Environment: used for building URLs
     *
     * @var string
     */
    protected $environment;

    /**
     * Region: used for building URLs
     *
     * @var string
     */
    protected $region;

    /**
     * {@inheritDoc}
     */
    public function setCampaignId(string $id): self
    {
        $this->campaignId = $id;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function setCashierId(string $id): self
    {
        $this->cashierId = $id;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function setEnvironment(string $env = SdkInterface::ENV_STAGING): self
    {
        $this->environment = $env;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function setRegion(string $region = SdkInterface::REGION_EU): self
    {
        $this->region = $region;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getCampaignLink(): string
    {
        $this->validateCampaignId();
        $this->validateRegion();
        $this->validateEnvironment();

        // Build game link
        $link = sprintf(
            'https://%s.%s/%s/online',
            $this->region,
            $this->getDomain(),
            $this->campaignId
        );

        return $link;
    }

    /**
     * {@inheritDoc}
     */
    public function getCashierLink(array $payload): string
    {
        $this->validateRegion();
        $this->validateEnvironment();
        $this->validateCashierId();

        $query = http_build_query($payload);
        $payload['signature'] = $this->sign($query);

        return sprintf(
            'https://%s-epos.%s/%s?%s',
            $this->region,
            $this->getDomain(),
            $this->cashierId,
            http_build_query($payload)
        );
    }

    /**
     * Returns the domain
     *
     * @return string
     */
    protected function getDomain(): string
    {
        return ($this->environment === SdkInterface::ENV_PRODUCTION) ? 'gb.run' : 'gb.plus';
    }

    /**
     * Validates campaign id
     *
     * @throws ValidationException
     */
    protected function validateCampaignId(): void
    {
        if (!preg_match('/^[A-z0-9]{5}$/', $this->campaignId)) {
            throw new ValidationException("Campaign ID is not valid");
        }
    }

    /**
     * Validates cashier id
     *
     * @throws ValidationException
     */
    protected function validateCashierId(): void
    {
        if (!preg_match('/^\d{5}$/', (string) $this->cashierId)) {
            throw new ValidationException("Cashier ID is not valid");
        }
    }

    /**
     * Validates region
     *
     * @throws ValidationException
     */
    protected function validateRegion(): void
    {
        if ($this->region !== SdkInterface::REGION_EU && $this->region !== SdkInterface::REGION_AM) {
            throw new ValidationException("Region is not valid should be either eu or am");
        }
    }

    /**
     * Validates environment
     *
     * @throws ValidationException
     */
    protected function validateEnvironment(): void
    {
        if (
            $this->environment !== SdkInterface::ENV_PRODUCTION &&
            $this->environment !== SdkInterface::ENV_STAGING
        ) {
            throw new ValidationException("Environment is not valid should be either canary or stable");
        }
    }
}
