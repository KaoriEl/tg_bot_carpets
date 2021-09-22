<?php

namespace App\Services\BotService;

class Context
{

    public static function StrategySelect($response): array
    {
        return MessageContext::CreateFromContext($response);
    }

}
