<?php

namespace App\Services\BotService;

use App\Contracts\ChatStrategy;
use App\Http\Controllers\TgUserController;

class CarpetsForWashing implements ChatStrategy
{
    private array $keyboard;

    public function __construct()
    {
        $this->keyboard = [
            'inline_keyboard' => [
                [
                    ['text' => 'Главное Меню', 'callback_data' => 'меню'],
                ]
            ]
        ];
    }

    public function HandleMessage($response): array
    {
        $TgUserController = new TgUserController();
        $return = $TgUserController->CheckUser($response, true);
        $TgUserController->UpdateStep($response, true,"works_with_carpets_for_washing");
        if ($return->status == "VERIFIED") {
            return $params = [
                'chat_id' => $response["callback_query"]["message"]["chat"]["id"],
                'text' => "Напишите id сделок через запятую",
                'parse_mode' => 'HTML',
            ];
        }else{
            return $params = [
                'chat_id' => $response["callback_query"]["message"]["chat"]["id"],
                'text' => "Вы не авторизованы в боте, обратитесь к администратору",
                'parse_mode' => 'HTML',
            ];
        }
    }
}
