<?php
/**
 * @author Jan Habbo Brüning <jan.habbo.bruening@gmail.com>
 */

namespace Frootbox\Paypal;

class Token
{
    /**
     *
     */
    public function __construct(
        private string $token,
        private string $expires,
    ) {}

    /**
     *
     */
    public function isValid(): bool
    {
        return ($this->expires - $_SERVER['REQUEST_TIME']) > 120;
    }

    /**
     *
     */
    public function getToken(): string
    {
        return $this->token;
    }
}
