<?php

namespace App\Http\Controllers;

use App\Models\FailedJobsAlbum;
use App\Services\BotService\Context;
use App\Services\BotService\MessageDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Api;
use Telegram\Bot\Exceptions\TelegramSDKException;
use Telegram\Bot\Laravel\Facades\Telegram;

class BotController extends Controller
{


    /**
     * @throws TelegramSDKException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function index()
    {
        $telegram = new Api(env("TELEGRAM_BOT_TOKEN"));
        $response = $telegram->getWebhookUpdate();
        Log::channel('debug-channel')->debug("--------Самое начало --------\n" . $response . "\n\n\n");


        //У меня не получилось запихать обработку альбомов в стратегию
        if (isset($response["message"]["media_group_id"]) && !isset($response["message"]["document"])){
            $TgUserController = new TgUserController();
            $user = $TgUserController->CheckUser($response, false);
            switch ($user->step) {
                case "works_with_carpets_from_clients":
                    (new CarpetsFromClientController())->album($response);
                break;
                case "works_with_carpets_before_washing":
                    (new CarpetsBeforeWashingController())->album($response);
                break;
            }
        }elseif (isset($response["message"]["document"])){
            (new MessageDocument())->index($telegram,$response);
            } else{
            $msg = Context::StrategySelect($response);
            $telegram->sendMessage($msg);
        }

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
        $offset = 870419091;
        $response = $telegram->getUpdates(['limit' => 1, 'offset' => $offset]);
        $params = [
            'chat_id'                  => $response[0]["message"]["chat"]["id"],
            'text'                     => "Обработка ошибок",
            'parse_mode'               => 'HTML',
        ];
        $telegram->sendMessage($params);
        dump($response);

    }

}
