<?php

namespace App\DataSaver;

use App\DataSaver\DataSaverInterface;

class LocalJsonDataSaver implements DataSaverInterface
{
    public function saveToLocalJsonFile(array $data): void
    {
        $jsonFilePath = getConfig('local_json_file');
        $jsonData = json_decode(file_get_contents($jsonFilePath), true) ?? [];

        $jsonData[] = $data;

        file_put_contents($jsonFilePath, json_encode($jsonData, JSON_PRETTY_PRINT));
    }
}
