<?php

namespace src\Decorator;

use DateTime;
use Exception;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerInterface;
use src\Integration\DataProvider;

class DecoratorManager extends DataProvider
{
  public $cache;
  public $logger;

  /**
   * @param string $host
   * @param string $user
   * @param string $password
   * @param CacheItemPoolInterface $cache
   */
  public function __construct($host, $user, $password, CacheItemPoolInterface $cache)
  {
    parent::__construct($host, $user, $password);
    $this->cache = $cache;
  }

  public function setLogger(LoggerInterface $logger)
  {
    $this->logger = $logger;
  }

  /**
   * {@inheritdoc}
   */
  public function getResponse(array $input)
  {
    try {
      $cacheKey = $this->getCacheKey($input);
      $cacheItem = $this->cache->getItem($cacheKey);
      if ($cacheItem->isHit()) {
        return $cacheItem->get();
      }

      // FIXME: здесь всегда будет вызываться родительская версия метода get(), поэтому если метод get() будет переопределен в данном классе, он не будет вызываться.
      $result = parent::get($input);

      $cacheItem
        ->set($result)
        ->expiresAt(
          (new DateTime())->modify('+1 day')
        );

      return $result;
    } catch (Exception $e) {
      // FIXME: logger может быть непроинициализирован, что вызовет ошибку.
      // FIXME: полезнее логировать ошибку, а не строку "Error".
      $this->logger->critical('Error');
    }

    return [];
  }

  // FIXME: здесь вероятно нужен protected доступ, поскольку метод скорее для внутреннего использования в рамках класса.
  public function getCacheKey(array $input)
  {
    return json_encode($input);
  }
}
