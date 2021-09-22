<?php

namespace App\Services\BotService;

use App\Http\Controllers\CarpetsBeforeWashingController;
use App\Http\Controllers\CarpetsFromClientController;
use App\Http\Controllers\ShelvesController;
use App\Http\Controllers\TgUserController;

class MessageWithPhoto
{
    /**
     * @throws \Telegram\Bot\Exceptions\TelegramSDKException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function CreateFromContext($response): array
    {
        $TgUserController = new TgUserController();
        $user = $TgUserController->CheckUser($response, false);
        switch ($user->step){
            case "works_with_carpets_from_clients":
                (new CarpetsFromClientController())->index($response, false);
                return $params = [
                    'chat_id'                  => $response["message"]["chat"]["id"],
                    'text'                     => "Сделка успешно сохранена",
                    'parse_mode'               => 'HTML',
                ];
            case "works_with_carpets_before_washing":
                (new CarpetsBeforeWashingController())->index($response, false);
                return $params = [
                    'chat_id'                  => $response["message"]["chat"]["id"],
                    'text'                     => "Сделка успешно сохранена",
                    'parse_mode'               => 'HTML',
                ];

            default:
                $TgUserController->UpdateStep($response, false,"wrong_message");
                return $params = [
                    'chat_id'                  => $response["message"]["chat"]["id"],
                    'text'                     => "Я не знаю такого сообщения, пожалуйста проверьте правильность написания",
                    'parse_mode'               => 'HTML',
                ];
        }

    }
}
