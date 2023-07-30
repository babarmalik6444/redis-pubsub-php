<?php

namespace App\DataSaver;

interface DataSaverInterface
{
    public function saveToLocalJsonFile(array $data): void;
}