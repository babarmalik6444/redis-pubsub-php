<?php

namespace App;

use App\DataProcessor\DefaultDataProcessor;
use App\MessageHandler\MessageValidator;
use Predis\Client;
use App\BotChecker\DefaultBotChecker;
use App\ConversionChecker\DefaultConversionChecker;
use App\MessageHandler\RedisMessagePublisher;
use App\DataSaver\LocalJsonDataSaver;

class RedisPubSubSubscriber
{
    private $redis;
    private $dataProcessor;

    public function __construct(
        private MessageValidator $messageValidator = new MessageValidator(),
        private DefaultBotChecker $botChecker = new DefaultBotChecker(),
        private DefaultConversionChecker $conversionChecker = new DefaultConversionChecker(),
        private RedisMessagePublisher $messagePublisher = new RedisMessagePublisher(new Client()),
        private LocalJsonDataSaver $dataSaver = new LocalJsonDataSaver()
    )
    {
        $this->redis = new Client(
            [
                'host' => getConfig('redis_host'),
                'port' => getConfig('redis_port'),
                'read_write_timeout' => 0
            ]
        );

        $this->dataProcessor = new DefaultDataProcessor(
            $this->messagePublisher,
            $this->botChecker,
            $this->conversionChecker,
            $this->messageValidator,
            $this->dataSaver
        );
    }

    public function subscribeToChannel()
    {
        $pubsub = $this->redis->pubSubLoop();
        $pubsub->subscribe('control_channel', getConfig('incoming_channel'));

        foreach ($pubsub as $message) {
            switch ($message->kind) {
                case 'subscribe':
                    break;

                case 'message':
                    if ($message->channel === 'control_channel') {
                        if ($message->payload === 'quit_loop') {
                            $pubsub->unsubscribe();
                        }
                    } else {
                        $this->dataProcessor->processMessage($message->payload);
                    }
                    break;
            }
        }
    }
}
