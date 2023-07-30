<?php

namespace App\DataProcessor;

use App\MessageHandler\MessagePublisherInterface;
use App\BotChecker\BotCheckerInterface;
use App\ConversionChecker\ConversionCheckerInterface;
use App\MessageHandler\MessageValidator;
use App\DataSaver\DataSaverInterface;

class DefaultDataProcessor implements DataProcessorInterface
{
    private $messagePublisher;
    private $botChecker;
    private $conversionChecker;
    private $messageValidator;
    private $dataSaver;

    public function __construct(
        MessagePublisherInterface $messagePublisher,
        BotCheckerInterface $botChecker,
        ConversionCheckerInterface $conversionChecker,
        MessageValidator $messageValidator,
        DataSaverInterface $dataSaver
    ) {
        $this->messagePublisher = $messagePublisher;
        $this->botChecker = $botChecker;
        $this->conversionChecker = $conversionChecker;
        $this->messageValidator = $messageValidator;
        $this->dataSaver = $dataSaver;
    }

    public function processMessage(string $message)
    {
        $messageData = json_decode($message, true);

        // Validate the message before processing
        if (!$this->messageValidator->validateMessage($messageData)) {
            return ;
        }

        $this->handleMessage($messageData);
    }

    private function handleMessage(array $messageData)
    {
        // Check if IP is a known bot address
        $isBot = $this->botChecker->isKnownBotAddress($messageData['ip']);

        // Check if URL contains "thank-you" indicating a conversion
        $isConversion = $this->conversionChecker->checkForConversion($messageData['url']);

        // Prepare outgoing message
        $outgoingMessage = [
            'key' => $messageData['key'],
            'timestamp' => $this->dateConverter($messageData['timestamp']),
            'isBot' => $isBot,
            'isConversion' => $isConversion,
        ];

        // Publish the outgoing message to another topic using MessagePublisherInterface
        $this->messagePublisher->publishMessage(getConfig('outgoing_channel'), json_encode($outgoingMessage));

        // Save as local JSON file if it's a conversion
        if ($isConversion) {
            $this->dataSaver->saveToLocalJsonFile($outgoingMessage);
        }
    }

    public function dateConverter(int $timestamp): string
    {
        $timestamp = $timestamp / 1000;
        $timestampInSeconds = (int)$timestamp;
        return date('c', $timestampInSeconds);
    }
}
