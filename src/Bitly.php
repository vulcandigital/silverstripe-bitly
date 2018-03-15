<?php

namespace Vulcan\Bitly;

use SilverStripe\Core\Config\Configurable;
use SilverStripe\Core\Injector\Injectable;

/**
 * Class Bitly
 * @package Vulcan\Bitly
 */
class Bitly
{
    use Injectable, Configurable;

    private static $api_key = false;

    protected $guzzle;

    /**
     * Bitly constructor.
     */
    public function __construct()
    {
        $this->guzzle = new \GuzzleHttp\Client([
            'base_uri' => 'https://api-ssl.bitly.com/v3/'
        ]);
    }

    /**
     * @return mixed
     */
    public function getApiKey()
    {
        if (!$key = $this->config()->get('api_key')) {
            throw new \RuntimeException('api_key must be defined');
        }

        return $key;
    }

    /**
     * @param $url
     *
     * @return mixed
     */
    public function shorten($url)
    {
        $response = $this->call('shorten', [
            'longUrl' => $url
        ]);

        if (!isset($response['data']['url'])) {
            throw new \RuntimeException('Something went wrong: ' . $response['status_txt']);
        }

        return $response['data']['url'];
    }

    /**
     * @param $url
     *
     * @return mixed
     */
    public function getClicksForUrl($url)
    {
        $response = $this->call('link/clicks', ['link' => $url]);

        if (!isset($response['data']['link_clicks'])) {
            throw new \RuntimeException('Something went wrong: ' . $response['status_txt']);
        }

        return $response['data']['link_clicks'];
    }

    /**
     * @param        $endpoint
     * @param array  $params
     * @param string $method
     *
     * @return mixed
     */
    public function call($endpoint, array $params = [], $method = 'GET')
    {
        $params = array_merge($params, ['access_token' => $this->getApiKey()]);
        $response = $this->guzzle->request($method, $endpoint, ['query' => $params]);

        return json_decode((string)$response->getBody(), true);
    }
}
