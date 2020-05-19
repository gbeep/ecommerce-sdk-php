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

namespace Gobeep\Ecommerce;

use Gobeep\Ecommerce\Exceptions\ValidationException;

/**
 * Gobeep ecommerce SDK (PHP library)
 */
class Sdk implements SdkInterface
{
    use Traits\Date;
    use Traits\Link;

    /**
     * Secret: used for signing URLs
     *
     * @var string
     */
    protected $secret;

    /**
     * {@inheritDoc}
     */
    public function setSecret(string $secret): self
    {
        $this->secret = $secret;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function sign(string $payload): string
    {
        $this->validateSecret();

        return base64_encode(hash_hmac('sha256', $payload, $this->secret, true));
    }

    /**
     * Validates secret
     *
     * @throws ValidationException
     */
    protected function validateSecret(): void
    {
        if (!preg_match('/^[a-z0-9]{32}$/', $this->secret)) {
            throw new ValidationException("Secret is not valid");
        }
    }
}
