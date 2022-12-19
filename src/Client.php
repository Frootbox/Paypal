<?php
/**
 * @author Jan Habbo Brüning <jan.habbo.bruening@gmail.com>
 */

namespace Frootbox\Paypal;

class Client
{
    private $accessToken = null;

    /**
     *
     */
    public function __construct(
        protected ?string $clientId = null,
        protected ?string $secret = null,
    ) {}

    /**
     *
     */
    public function get(string $path, array $pathData = null, array $payload = null): array
    {
        // Obtain get token
        $accessToken = $this->getAccessToken();

        // Build url
        $url = 'https://api.paypal.com' . $path;

        if (!empty($pathData)) {
            $url .= '?' . http_build_query($pathData);
        }

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_HTTPHEADER => array(
                "Accept: application/json",
                "Content-Type: application/json",
                "Authorization: Bearer " . $accessToken->getToken(),
            ),
        ));

        if (!empty($payload)) {
            $payload = json_encode($payload);

            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
        }

        $result = curl_exec($curl);

        if ($result === false) {

            d("ERROR");

            d(curl_error($curl));

        }

        $response = json_decode($result, true);

        return $response;
    }

    /**
     *
     */
    public function getAccessToken(): \Frootbox\Paypal\Token
    {
        if (!empty($_SESSION['paypal']['token']['accessToken'])) {

            $accessToken = new Token(
                token: $_SESSION['paypal']['token']['accessToken'],
                expires: $_SESSION['paypal']['token']['expires'],
            );

            if ($accessToken->isValid()) {
                return $accessToken;
            }
        }

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.paypal.com/v1/oauth2/token",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_USERPWD => $this->clientId . ':' . $this->secret,
            CURLOPT_POSTFIELDS => "grant_type=client_credentials",
            CURLOPT_HTTPHEADER => array(
                "Accept: application/json",
                "Accept-Language: en_US"
            ),
        ));

        $result = curl_exec($curl);
        $data = json_decode($result, true);

        $_SESSION['paypal']['token']['accessToken'] = $data['access_token'];
        $_SESSION['paypal']['token']['expires'] = $_SERVER['REQUEST_TIME'] + $data['expires_in'];

        $this->accessToken = new Token(
            token: $_SESSION['paypal']['token']['accessToken'],
            expires: $_SESSION['paypal']['token']['expires'],
        );

        return $this->accessToken;
    }

    /**
     *
     */
    public function getSubscription(string $subscriptionId): \Frootbox\Paypal\Subscription
    {
        $result = $this->get('/v1/billing/subscriptions/' . $subscriptionId);

        return \Frootbox\Paypal\Subscription::fromData(client: $this, subscriptionId: $subscriptionId, data: $result);
    }
}
