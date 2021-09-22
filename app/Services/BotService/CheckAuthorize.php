<?php

namespace App\Services\BotService;

use App\Contracts\ChatStrategy;
use App\Http\Controllers\TgUserController;
use App\Services\Engine\KeyboardGenerate;

class CheckAuthorize implements ChatStrategy
{
    private array $keyboard;

    public function __construct()
    {
        $this->keyboard = [
            'inline_keyboard' => [
                [
                    ['text' => 'Группы', 'callback_data' => 'группы'],
                    ['text' => 'Истории', 'callback_data' => 'истории'],
                    ['text' => 'Сделки', 'callback_data' => 'сделки'],
                ]
            ]
        ];
    }

    public function HandleMessage($response): array
    {
        $TgUserController = new TgUserController();
        $return = $TgUserController->CheckUser($response, true);
        $TgUserController->UpdateStep($response, true, "view_unauthorized_users");

        if ($return->status == "VERIFIED" && $return->role == "admin") {
            $unauthorized_users = (new TgUserController())->GetUnauthorizedUsers();
            if ($unauthorized_users != "Bad addition") {
                $data = array();
                foreach ($unauthorized_users as $unauthorized_user) {
                    array_push($data, $unauthorized_user->name . " - " . $unauthorized_user->tg_nickname . ",авторизовать*" . $unauthorized_user->tg_nickname);
                }
                $keyboard = (new KeyboardGenerate($this->keyboard))->generate($data, "new");
                $encodedKeyboard = json_encode($keyboard);
                return $params = [
                    'chat_id' => $response["callback_query"]["message"]["chat"]["id"],
                    'text' => "Эти пользователи не авторизованы\nНажмите на одного из них для авторизации",
                    'parse_mode' => 'HTML',
                    'reply_markup' => $encodedKeyboard
                ];
            } else {
                $data = ["Заявки на авторизацию,заявки"];
                $keyboard = (new KeyboardGenerate($this->keyboard))->generate($data);
                $encodedKeyboard = json_encode($keyboard);
                return $params = [
                    'chat_id' => $response["callback_query"]["message"]["chat"]["id"],
                    'text' => "Запрос на авторизацию нет.",
                    'parse_mode' => 'HTML',
                    'reply_markup' => $encodedKeyboard
                ];
            }


        } else {
            return $params = [
                'chat_id' => $response["callback_query"]["message"]["chat"]["id"],
                'text' => "Вы не авторизованы в боте, обратитесь к администратору",
                'parse_mode' => 'HTML',
            ];
        }
    }
}
