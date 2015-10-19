<?php

namespace Zoho\Subscription\Api;

use Zoho\Subscription\Client\Client;

/**
 * Subscription
 *
 * @author Elodie Nazaret <elodie@yproximite.com>
 *
 * @link https://www.zoho.com/subscriptions/api/v1/#subscriptions
 */
class Subscription extends Client
{
    /**
     * @param array $data
     *
     * @throws \Exception
     *
     * @return string
     */
    public function createSubscription($data)
    {
        $response = $this->client->request('POST', 'subscriptions', [
            'json' => json_encode($data)
        ]);

        $hasSucceeded = $this->processResponse($response);

        return $hasSucceeded;
    }

    /**
     * @param string $subscriptionId The subscription's id
     * @param array $data
     *
     * @throws \Exception
     *
     * @return string
     */
    public function buyOneTimeAddonForASubscription($subscriptionId, $data)
    {
        $response = $this->client->request('POST', sprintf('subscriptions/%s/buyonetimeaddon', $subscriptionId), [
            'json' => json_encode($data)
        ]);

        $hasSucceeded = $this->processResponse($response);

        return $hasSucceeded;
    }

    /**
     * @param string $subscriptionId The subscription's id
     * @param string $couponCode The coupon's code
     *
     * @throws \Exception
     *
     * @return array
     */
    public function associateCouponToASubscription($subscriptionId, $couponCode)
    {
        $response = $this->client->request('POST', sprintf('subscriptions/%s/coupons/%s', $subscriptionId, $couponCode));

        $hasSucceeded = $this->processResponse($response);

        return $hasSucceeded;
    }

    /**
     * @param string $subscriptionId The subscription's id
     *
     * @throws \Exception
     *
     * @return string
     */
    public function reactivateSubscription($subscriptionId)
    {
        $response = $this->client->request('POST', sprintf('subscriptions/%s/reactivate', $subscriptionId));

        $hasSucceeded = $this->processResponse($response);

        return $hasSucceeded;
    }

    /**
     * @param string $subscriptionId The subscription's id
     *
     * @throws \Exception
     *
     * @return array
     */
    public function getSubscription($subscriptionId)
    {
        $cacheKey = sprintf('zoho_subscription_%s', $subscriptionId);
        $hit      = $this->getFromCache($cacheKey);

        if (false === $hit) {
            $response = $this->client->request('GET', sprintf('subscriptions/%s', $subscriptionId));

            $result = $this->processResponse($response);

            $subscription = $result['subscription'];

            $this->saveToCache($cacheKey, $subscription);

            return $subscription;
        }

        return $hit;
    }
}