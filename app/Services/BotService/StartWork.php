<?php

namespace App\Services\BotService;

use App\Contracts\ChatStrategy;
use App\Http\Controllers\BotController;
use App\Http\Controllers\TgUserController;
use App\Services\Engine\KeyboardGenerate;

class StartWork implements ChatStrategy
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

    /**
     * @throws \Telegram\Bot\Exceptions\TelegramSDKException
     */
    public function HandleMessage($response): array
    {
        $TgUserController = new TgUserController();
        $return = $TgUserController->CheckUser($response, false);
        $TgUserController->UpdateStep($response, false, "start_work");
        if ($return->status == "VERIFIED" || $return != "Bad addition") {
            if ($return->role == "admin") {
                $data = ["Заявки на авторизацию,заявки"];
                $keyboard = (new KeyboardGenerate($this->keyboard))->generate($data);
            } else {
                $keyboard = $this->keyboard;
            }


            $encodedKeyboard = json_encode($keyboard);

            $keyboard_infinity = [
                'keyboard' => [
                    [
                        ['text' => '☰'],
                        ['text' => '← Назад'],
                    ]
                ],
                'resize_keyboard' => true,
                'one_time_keyboard' => false
            ];

            $encodedKeyboardInfinity = json_encode($keyboard_infinity);

            $msg = $params = [
                'chat_id' => $response["message"]["chat"]["id"],
                'text' => "Здравствуйте!",
                'parse_mode' => 'HTML',
                'reply_markup' => $encodedKeyboardInfinity,
            ];

            (new BotController())->send_another_user($msg);


            return $params = [
                'chat_id' => $response["message"]["chat"]["id"],
                'text' => "Старт работы с ботом: \n" . "Дата: " . date("m.d.y") . "\nВремя: " . date("H:i:s"),
                'parse_mode' => 'HTML',
                'reply_markup' => $encodedKeyboard,

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
