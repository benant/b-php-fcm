<?php
namespace benant\bPhpFCM;

use GuzzleHttp;

/**
 * @author palbertini
 */
class Client implements ClientInterface
{
    const DEFAULT_API_URL = 'https://fcm.googleapis.com/fcm/send';

    /** @var string */
    private $apiKey;

    /** @var string */
    private $proxyApiUrl;

    /** @var GuzzleHttp\ClientInterface */
    private $guzzleClient;

    public function injectHttpClient(GuzzleHttp\ClientInterface $client)
    {
        $this->guzzleClient = $client;
    }

    /**
     * add your server api key here
     * read how to obtain an api key here: https://firebase.google.com/docs/server/setup#prerequisites
     *
     * @param string $apiKey
     *
     * @return \benant\bPhpFCM\Client
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
        return $this;
    }

    /**
     * people can overwrite the api url with a proxy server url of their own
     *
     * @param string $url
     *
     * @return \benant\bPhpFCM\Client
     */
    public function setProxyApiUrl($url)
    {
        $this->proxyApiUrl = $url;
        return $this;
    }

    /**
     * sends your notification to the google servers and returns a guzzle repsonse object
     * containing their answer.
     *
     * @param Message $message
     *
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\RequestException
     */
    public function send(Message $message)
    {
        return $this->guzzleClient->post(
            $this->getApiUrl(),
            [
                'headers' => [
                    'Authorization' => sprintf('key=%s', $this->apiKey),
                    'Content-Type' => 'application/json'
                ],
                'body' => json_encode($message)
            ]
        );
    }

    private function getApiUrl()
    {
        return isset($this->proxyApiUrl) ? $this->proxyApiUrl : self::DEFAULT_API_URL;
    }
}