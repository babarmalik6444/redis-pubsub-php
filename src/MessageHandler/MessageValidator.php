<?php

namespace App\MessageHandler;

class MessageValidator
{
    public function validateMessage(mixed $message): bool
    {
        //check if data is arrayed
        if (!is_array($message)) {
            return false;
        }

        // Check if all required fields are present
        if (
            !isset($message['key']) ||
            !isset($message['timestamp']) ||
            !isset($message['ip']) ||
            !isset($message['url'])
        ) {
            return false;
        }

        // Check if the fields have correct data types and formats
        if (
            !is_string($message['key']) ||
            !is_numeric($message['timestamp']) ||
            !is_string($message['ip']) ||
            !is_string($message['url']) ||
            !filter_var($message['ip'], FILTER_VALIDATE_IP) ||
            !filter_var($message['url'], FILTER_VALIDATE_URL)
        ) {
            return false;
        }

        return true;
    }
}
