<?php

namespace App\Services\BotService;

use App\Http\Controllers\CarpetsBeforeWashingController;
use App\Http\Controllers\CarpetsFromClientController;
use App\Http\Controllers\ShelvesController;
use App\Http\Controllers\TgUserController;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Exceptions\TelegramSDKException;

class MessageWithPhoto
{
    /**
     * @throws TelegramSDKException
     * @throws GuzzleException
     */
    public function CreateFromContext($response): array
    {
        $TgUserController = new TgUserController();
        $user = $TgUserController->CheckUser($response, false);
        switch ($user->step) {
            case "works_with_carpets_from_clients":
                Log::channel('debug-channel')->debug("-------- user -> step = works_with_carpets_from_clients--------\n" . $response . "\n\n\n");
                $status = (new CarpetsFromClientController())->index($response);
                if ($status == "no id") {
                    Log::channel('debug-channel')->debug("-------- user -> step = works_with_carpets_from_clients and user don't write id deals--------\n" . $response . "\n\n\n");
                    return $params = [
                        'chat_id' => $response["message"]["chat"]["id"],
                        'text' => "<b>Вы не написали id сделки</b>\nНапишите id сделки и прикрепите фото еще раз.",
                        'parse_mode' => 'HTML',
                    ];
                } else {
                    return $params = [
                        'chat_id' => $response["message"]["chat"]["id"],
                        'text' => "Сделка успешно сохранена",
                        'parse_mode' => 'HTML',
                    ];
                }
                break;
            case "works_with_carpets_before_washing":
                Log::channel('debug-channel')->debug("-------- user -> step = works_with_carpets_before_washing--------\n" . $response . "\n\n\n");
                $status = (new CarpetsBeforeWashingController())->index($response, false);
                if ($status == "no id") {
                    Log::channel('debug-channel')->debug("-------- user -> step = works_with_carpets_before_washing and user don't write id deals--------\n" . $response . "\n\n\n");
                    return $params = [
                        'chat_id' => $response["message"]["chat"]["id"],
                        'text' => "<b>Вы не написали id сделки</b>\nНапишите id сделки и прикрепите фото еще раз.",
                        'parse_mode' => 'HTML',
                    ];
                } else {
                    return $params = [
                        'chat_id' => $response["message"]["chat"]["id"],
                        'text' => "Сделка успешно сохранена",
                        'parse_mode' => 'HTML',
                    ];
                }
                break;

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
