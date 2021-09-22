<?php

namespace App\Services\BotService;

use App\Contracts\ChatStrategy;
use App\Http\Controllers\TgUserController;
use App\Services\Engine\KeyboardGenerate;

class GroupBack implements ChatStrategy
{
    private array $keyboard;

    public function __construct()
    {
        $this->keyboard = [
            'inline_keyboard' => [
                [

                ]
            ]
        ];
    }

    public function HandleMessage($response): array
    {
        $TgUserController = new TgUserController();
        $return = $TgUserController->CheckUser($response, false);
        $TgUserController->UpdateStep($response, false, "watching_groups");
        if ($return->status == "VERIFIED") {

            $data = ["Полки,полки", "Ковры от клиента,от клиента", "Ковры до стирки,до стирки", "Ковры на стирку,на стирку"];
            $keyboard = (new KeyboardGenerate($this->keyboard))->generate($data);
            $encodedKeyboard = json_encode($keyboard);

            return $params = [
                'chat_id' => $response["message"]["chat"]["id"],
                'text' => "Выберите подходящий для вас вариант",
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
