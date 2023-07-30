<?php

namespace App\MessageHandler;

interface MessagePublisherInterface
{
    public function publishMessage(string $channel, array $message);
}
