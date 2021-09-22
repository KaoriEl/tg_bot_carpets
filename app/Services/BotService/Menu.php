<?php

namespace App\Services\BotService;

use App\Contracts\ChatStrategy;
use App\Http\Controllers\TgUserController;
use App\Services\Engine\KeyboardGenerate;

class Menu implements ChatStrategy
{
    private array $keyboard;

    public function __construct()
    {
        $this->keyboard = [
            'inline_keyboard' => [
                [
                    ['text' => 'Группы', 'callback_data' => 'группы'],
                    ['text' => 'Истории', 'callback_data' => 'истории'],
                    ['text' => 'Сделки', 'callback_data' => 'сделки']
                ]
            ]
        ];
    }

    public function HandleMessage($response): array
    {
        $TgUserController = new TgUserController();
        $return = $TgUserController->CheckUser($response, false);
        $TgUserController->UpdateStep($response, false, "In_the_main_menu");
        if ($return->status == "VERIFIED") {
            if ($return->role == "admin") {
                $data = ["Заявки на авторизацию,заявки"];
                $keyboard = (new KeyboardGenerate($this->keyboard))->generate($data);
            } else {
                $keyboard = $this->keyboard;
            }
            $encodedKeyboard = json_encode($keyboard);

            return $params = [
                'chat_id' => $response["message"]["chat"]["id"],
                'text' => "Главное меню",
                'parse_mode' => 'HTML',
                'reply_markup' => $encodedKeyboard
            ];
        } else {
            return $params = [
                'chat_id' => $response["message"]["chat"]["id"],
                'text' => "Вы не авторизованы в боте, обратитесь к администратору",
                'parse_mode' => 'HTML',
            ];
        }

    }
}
