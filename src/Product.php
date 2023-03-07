<?php
/**
 * @author Jan Habbo Brüning <jan.habbo.bruening@gmail.com>
 */

namespace Frootbox\Paypal;

class Product
{
    protected string $id;
    protected string $name;
    protected string $description;

    protected \Frootbox\Paypal\Client $client;

    /**
     * @param Client $client
     * @param string $productId
     */
    public function __construct(
        \Frootbox\Paypal\Client $client,
        string $productId,
    )
    {
        $this->id = $productId;
        $this->client = $client;
    }

    /**
     * @param Client $client
     * @param string $planId
     * @param array $data
     * @return static
     */
    public static function fromData(\Frootbox\Paypal\Client $client, string $productId, array $data): self
    {
        $product = new self($client, $productId);
        $product->id = $data['id'];
        $product->name = $data['name'];
        $product->description = $data['description'];

        return $product;
    }
}
