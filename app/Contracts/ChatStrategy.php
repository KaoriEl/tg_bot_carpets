<?php

namespace App\Contracts;

use Illuminate\Http\Request;
use Telegram\Bot\Api;

interface ChatStrategy
{
    public function HandleMessage($response): array;
}
