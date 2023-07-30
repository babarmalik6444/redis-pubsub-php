# Redis PubSub PHP

In this project, In have used php 8.1. 
Project Consume and Publish message to Redis channels. 
Project is developed considering SOLID principles. 

# Installation
Clone this repo from following url
```sh
https://github.com/babarmalik6444/redis-pubsub-php.git
```
Navigate to project folder
```sh
cd redis-pubsub-php
```

Add configurations in config.php

```sh
[
    'redis_host' => '127.0.0.1',
    'redis_port' => 6379,
    'incoming_channel' => 'visitor-analytics',
    'outgoing_channel' => 'visitor-analytics-2',
    'local_json_file' => __DIR__ . '/../conversions.json',
]
```

Run the Redis server on your system and create the channels

Run index.php
```sh
php index.php
```

Project will start listening to the provided 'incoming_channel'. 
Project Accept messages in following format and process only if all fields are provided and valid

```sh
{
  "key": "customerId0b50c1a7-d006-4661-960c-10b88da8ee05metadata",
  "timestamp": "1673889466163",
  "ip": "103.147.42.101",
  "url": "https://example.com/product/some-product"
}
{
  "key": "customerId0b50c1a7-d006-4661-960c-10b88da8ee05metadata",
  "timestamp": "1673889466163",
  "ip": "66.249.79.96",
  "url": "https://example.com/checkout/thank-you"
}
```

Project will publish the message to provided 'outgoing_channel' after processing in following format and save it in local json file 

```sh
{
  "key": "customerId0b50c1a7-d006-4661-960c-10b88da8ee05metadata",
  "timestamp": "2023-01-16T17:17:46.163Z",
  "isBot": true,
  "isConversion": false
}
{
  "key": "customerId0b50c1a7-d006-4661-960c-10b88da8ee05metadata",
  "timestamp": "2023-01-16T17:17:46.163Z",
  "isBot": false,
  "isConversion": true
}
```