<?php

namespace App\DataProcessor;

interface DataProcessorInterface
{
    public function processMessage(string $message);
}
