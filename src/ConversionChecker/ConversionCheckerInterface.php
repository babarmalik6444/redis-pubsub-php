<?php

namespace App\ConversionChecker;

interface ConversionCheckerInterface
{
    public function checkForConversion(string $url): bool;
}
