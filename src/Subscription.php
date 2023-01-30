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

    protected \DateTime $dateCreated;
    protected \DateTime $dateNextBilling;

    protected array $subscriber;
    protected array $billingInfo;

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
    public function cancel(string $reason, $parameters = []): void
    {
        if ($this->status == 'CANCELLED') {
            return;
        }

        $result = $this->client->get('/v1/billing/subscriptions/' . $this->subscriptionId . '/cancel', null, [
            'reason' => $reason,
        ]);
    }

    /**
     *
     */
    public function getBillingInfo(): array
    {
        return $this->billingInfo;
    }

    /**
     * @return \DateTime
     */
    public function getDateCreated(): \DateTime
    {
        return $this->dateCreated;
    }

    /**
     * @return \DateTime
     */
    public function getDateNextBilling(): \DateTime
    {
        return $this->dateNextBilling;
    }

    /**
     *
     */
    public function getPlanId(): string
    {
        return $this->planId;
    }

    /**
     *
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    public function getSubscriber()
    {
        return $this->subscriber;
    }

    /**
     * @return string
     */
    public function getSubscriptionId(): string
    {
        return $this->subscriptionId;
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

        $subscription->dateCreated = new \DateTime($data['create_time']);

        if (!empty($data['billing_info']['next_billing_time'])) {
            $subscription->dateNextBilling = new \DateTime($data['billing_info']['next_billing_time']);
        }

        $subscription->status = $data['status'];
        $subscription->planId = $data['plan_id'];
        $subscription->subscriber = $data['subscriber'];
        $subscription->billingInfo = $data['billing_info'];

        return $subscription;
    }
}
