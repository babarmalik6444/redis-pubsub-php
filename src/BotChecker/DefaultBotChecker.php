<?php

namespace App\BotChecker;

class DefaultBotChecker implements BotCheckerInterface
{
    public function isKnownBotAddress(string $ip): bool
    {
        $botAddressesUrl = 'https://antoinevastel.com/data/avastel-longtime-bot-ips.txt';
        $knownBotAddresses = file($botAddressesUrl, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        return in_array($ip, $knownBotAddresses);
    }
}
