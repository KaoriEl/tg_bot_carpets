<?php

namespace App\Http\Controllers;

use App\Services\BotService\Context;
use Illuminate\Http\Request;
use Telegram\Bot\Api;
use Telegram\Bot\Exceptions\TelegramSDKException;
use Telegram\Bot\Laravel\Facades\Telegram;

class BotController extends Controller
{


    /**
     * @throws TelegramSDKException
     */
    public function index()
    {
        $telegram = new Api(env("TELEGRAM_BOT_TOKEN"));
        $response = $telegram->getWebhookUpdate();
        $msg = Context::StrategySelect($response);
        $telegram->sendMessage($msg);
    }

    /**
     * @throws TelegramSDKException
     */
    public function send_another_user($msg)
    {
        $telegram = new Api(env("TELEGRAM_BOT_TOKEN"));
        $telegram->sendMessage($msg);
    }

    /**
     * @throws TelegramSDKException
     */
    public function skip_update(){
        $telegram = new Api(env("TELEGRAM_BOT_TOKEN"));
        $offset = 663511828;
        $response = $telegram->getUpdates(['limit' => 1, 'offset' => $offset]);
        $params = [
            'chat_id'                  => $response[0]["message"]["chat"]["id"],
            'text'                     => "Здравствуйте, запрос на авторизацию отправлен, пожалуйста, ожидайте.",
            'parse_mode'               => 'HTML',
        ];
        $telegram->sendMessage($params);
        dump($response);

    }

}
