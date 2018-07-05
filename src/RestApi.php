<?php
namespace Ebb;
// use Symfony\Component\Cache\Simple\FilesystemCache;
class RestApi {

  function __construct($baseUrl, \Psr\SimpleCache\CacheInterface $cache){
    $this->baseUrl = $baseUrl;
    $this->cache = $cache;
  }

  function query($entity, $action, $params = [], $cache = true) {
    $key = md5(serialize([$entity, $action, $params]));
    if($cache && substr($action, 0, 3) && $this->cache->hasItem($key)){
      return $this->cache->get($key);
    }
    $json = json_encode($params);
    $result = json_decode(file_get_contents(
      $this->baseUrl .
        "&entity={$entity}" .
        "&action={$action}" .
        "&json={$json}"
    ),1);
    $this->cache->set($key, $result);
    return $result;
  }
}
