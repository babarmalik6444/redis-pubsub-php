<?php

namespace App\MessageHandler;

use Predis\Client;

class RedisMessagePublisher implements MessagePublisherInterface
{
    public function __construct(
        private Client $publishRedis
    ) {}

    public function publishMessage($channel, $message)
    {
        $this->publishRedis->publish($channel, $message);
    }
}
