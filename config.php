<?php

return [
    'redis_host' => '127.0.0.1',
    'redis_port' => 6379,
    'incoming_channel' => 'visitor-analytics',
    'outgoing_channel' => 'visitor-analytics-2',
    'local_json_file' => __DIR__ . '/../conversions.json',
    'botAddresses' => 'https://antoinevastel.com/data/avastel-longtime-bot-ips.txt',
    "redis_cache_expiry" =>  3600, //seconds
    "redis_cache_key" =>  'known_bot_addresses'
];
