<?php
namespace Ebb;

use Symfony\Component\Cache\Simple\FilesystemCache;
use GuzzleHttp\Client;

class RestApi
{
    public function __construct($baseUrl, $cache, $flush = false)
    {
        $this->baseUrl = $baseUrl;
        $this->cache = $cache;
        $this->flush = $flush;
    }

    public function query($entity, $action, $params = [])
    {
        $key = md5(serialize([$entity, $action, $params]));
        if (!$this->flush && substr($action, 0, 3) == 'get' && $this->cache->hasItem($key)) {
            return $this->cache->get($key);
        }
        $json = json_encode($params);
        $client = new Client(['http_errors' => false]);
        $url = $this->baseUrl . "&entity={$entity}&action={$action}&json={$json}";
        $response = $client->request('GET', $url);
        if ($response->getStatusCode() >= 400) {
            $errorMessage = "API error: {$response->getStatusCode()}: {$response->getReasonPhrase()} ($url)";
            throw new \Exception($errorMessage);
        }
        $result = json_decode($response->getBody(), 1);
        if ($result['is_error']) {
            $errorMessage = "API error: {$result['error_message']}";
            throw new \Exception($errorMessage);
        }
        $this->cache->set($key, $result);
        return $result;
    }
}
