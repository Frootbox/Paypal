<?php
/**
 * @author Jan Habbo Brüning <jan.habbo.bruening@gmail.com>
 */

namespace Frootbox\Paypal;

class Subscription
{
    /**
     *
     */
    public function __construct(
        protected \Frootbox\Paypal\Client $client,
        protected string $subscriptionId,
    ) {}

    /**
     *
     */
    public function getTransactions(): array
    {

        $result = $this->client->get('/v1/billing/subscriptions/' . $this->subscriptionId . '/transactions', [
            'start_time' => '2018-01-21T07:50:20.940Z',
            'end_time' => '2022-10-31T07:50:20.940Z',
        ]);

        d($result);
    }
}
