<?php
/**
 * @author Jan Habbo Brüning <jan.habbo.bruening@gmail.com>
 */

namespace Frootbox\Paypal;

class Plan
{
    protected string $id;
    protected string $productId;
    protected string $status;

    protected \Frootbox\Paypal\Client $client;

    /**
     * @param Client $client
     * @param string $planId
     */
    public function __construct(
        \Frootbox\Paypal\Client $client,
        string $planId,
    )
    {
        $this->id = $planId;
        $this->client = $client;
    }

    /**
     * @param Client $client
     * @param string $planId
     * @param array $data
     * @return static
     */
    public static function fromData(\Frootbox\Paypal\Client $client, string $planId, array $data): self
    {
        $plan = new self($client, $planId);

        $plan->status = $data['status'];
        $plan->id = $data['id'];
        $plan->productId = $data['product_id'];

        return $plan;
    }

    /**
     *
     */
    public function getProduct(): \Frootbox\Paypal\Product
    {
        $result = $this->client->get('/v1/catalogs/products/' . $this->productId);

        return \Frootbox\Paypal\Product::fromData(client: $this->client, productId: $this->productId, data: $result);
    }
}
