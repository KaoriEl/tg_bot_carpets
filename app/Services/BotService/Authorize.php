<?php

namespace App\Services\BotService;

use App\Contracts\ChatStrategy;
use App\Http\Controllers\BotController;
use App\Http\Controllers\TgUserController;
use App\Services\Engine\KeyboardGenerate;
use Telegram\Bot\Api;

class Authorize implements ChatStrategy
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
        //Ну не очень красиво кнш, но вроде понятно.
        $TgUserController = new TgUserController();
        $get_tgnickname = explode("*", mb_strtolower($response["callback_query"]['data']));
        $return = $TgUserController->CheckUser($response, true);
        $TgUserController->UpdateStep($response, true,"authorize");
        $status_auth = $TgUserController->AuthorizeUser($response, $get_tgnickname[1]);

        if ($return->status == "VERIFIED" && $return->role == "admin") {
            $data = ["Заявки на авторизацию,заявки"];
            $keyboard = (new KeyboardGenerate($this->keyboard))->generate($data);
            $encodedKeyboard = json_encode($keyboard);
            if ($status_auth->status == "VERIFIED" ) {

                $msg = [
                    'chat_id' => $status_auth->chat_id,
                    'text' => "<b>" . $get_tgnickname[1] . "</b> Вас авторизировали в системе!\n Для работы с ботом напишите: <b>Начать работу</b>",
                    'parse_mode' => 'HTML',
                ];

                (new BotController())->send_another_user($msg);

                return $params = [
                    'chat_id' => $response["callback_query"]["message"]["chat"]["id"],
                    'text' => "Пользователь с телеграмм ником <b>" . $get_tgnickname[1] . "</b> \nУспешно авторизирован",
                    'parse_mode' => 'HTML',
                    'reply_markup' => $encodedKeyboard
                ];
            } else {
                return $params = [
                    'chat_id' => $response["callback_query"]["message"]["chat"]["id"],
                    'text' => "Ой, что-то пошло не так, ошибка авторизации.",
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
