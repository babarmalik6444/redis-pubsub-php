<?php

namespace App\BotChecker;

use Predis\Client;

class DefaultBotChecker implements BotCheckerInterface
{
    private $redis;

    public function __construct()
    {
        $this->redis = new Client([
            'host' => getConfig('redis_host'),
            'port' => getConfig('redis_port')
        ]);
    }

    public function isKnownBotAddress(string $ip): bool
    {
        $cachedKnownBotAddresses = $this->getFromCache(getConfig('redis_cache_key'));

        if ($cachedKnownBotAddresses === null) {
            $knownBotAddresses = $this->fetchKnownBotAddresses();
            $this->storeInCache(getConfig('redis_cache_key'), $knownBotAddresses, getConfig('redis_cache_expiry'));
        } else {
            $knownBotAddresses = json_decode($cachedKnownBotAddresses, true);
        }

        return in_array($ip, $knownBotAddresses);
    }

    private function fetchKnownBotAddresses(): array
    {
        $botAddressesUrl = getConfig('botAddresses');
        $lines = file($botAddressesUrl, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];

        $ipAddresses = [];
        foreach ($lines as $line) {
            $data = explode(";", $line);
            $ipAddresses[] = $data[0];
        }

        return $ipAddresses;
    }

    private function getFromCache(string $key)
    {
        return $this->redis->get($key);
    }

    private function storeInCache(string $key, $data, int $ttl)
    {
        $this->redis->setex($key, $ttl, json_encode($data));
    }
}
