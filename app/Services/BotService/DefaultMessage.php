<?php

namespace App\Services\BotService;

use App\Contracts\ChatStrategy;
use App\Http\Controllers\CarpetsForWashingController;
use App\Http\Controllers\CarpetsFromClientController;
use App\Http\Controllers\ShelvesController;
use App\Http\Controllers\TgUserController;

class DefaultMessage implements ChatStrategy
{

    /**
     * @throws \Telegram\Bot\Exceptions\TelegramSDKException
     */
    public function HandleMessage($response): array
    {
        $TgUserController = new TgUserController();
        $user = $TgUserController->CheckUser($response, false);
        switch ($user->step) {
            case "works_with_shelves":
                (new ShelvesController())->index($response, false);
                return $params = [
                    'chat_id' => $response["message"]["chat"]["id"],
                    'text' => "Сделка успешно сохранена",
                    'parse_mode' => 'HTML',
                ];
            case "works_with_carpets_for_washing":
                (new CarpetsForWashingController())->index($response, false);
                return $params = [
                    'chat_id' => $response["message"]["chat"]["id"],
                    'text' => "Сделка успешно сохранена",
                    'parse_mode' => 'HTML',
                ];
            default:
                $TgUserController->UpdateStep($response, false, "wrong_message");
                return $params = [
                    'chat_id' => $response["message"]["chat"]["id"],
                    'text' => "Я не знаю такого сообщения, пожалуйста проверьте правильность написания",
                    'parse_mode' => 'HTML',
                ];
        }

    }
}
