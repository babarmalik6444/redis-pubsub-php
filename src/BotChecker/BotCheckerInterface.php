<?php

namespace App\BotChecker;

interface BotCheckerInterface
{
    public function isKnownBotAddress(string $ip): bool;
}
