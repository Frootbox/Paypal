<?php
/**
 * @author Jan Habbo Brüning <jan.habbo.bruening@gmail.com>
 */

namespace Frootbox\Paypal;

class Subscription
{
    protected $subscriptionId;
    protected $status;
    protected $planId;

    protected $client;

    /**
     *
     */
    public function __construct(
        \Frootbox\Paypal\Client $client,
        string $subscriptionId,
    )
    {
        $this->subscriptionId = $subscriptionId;
        $this->client = $client;
    }

    /**
     *
     */
    public function cancel(): array
    {
        try {

            $result = $this->client->get('/v1/billing/subscriptions/' . $this->subscriptionId . '/cancel', null, [
                'reason' => 'Kündigung'
            ]);

            d($result);
        }
        catch (\Exception $e) {
            d($e);
        }
    }

    /**
     *
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     *
     */
    public function getTransactions(): array
    {
        $result = $this->client->get('/v1/billing/subscriptions/' . $this->subscriptionId . '/transactions', [
            'start_time' => '2018-01-21T07:50:20.940Z',
            'end_time' => '2023-10-31T07:50:20.940Z',
        ]);

        return $result;
    }

    /**
     *
     */
    public static function fromData(\Frootbox\Paypal\Client $client, string $subscriptionId, array $data): self
    {
        $subscription = new self($client, $subscriptionId);

        $subscription->status = $data['status'];
        $subscription->planId = $data['plan_id'];

        return $subscription;
    }
}
