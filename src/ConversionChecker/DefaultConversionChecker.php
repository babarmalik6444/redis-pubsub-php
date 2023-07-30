<?php

namespace App\ConversionChecker;

class DefaultConversionChecker implements ConversionCheckerInterface
{
    public function checkForConversion(string $url): bool
    {
        return strpos($url, 'thank-you') !== false;
    }
}
