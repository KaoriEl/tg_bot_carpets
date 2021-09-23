<?php

namespace App\Http\Controllers;

use App\Models\CustomerCarpets;
use App\Models\RugsBeforeWashing;
use App\Services\Random\RandomString;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Telegram\Bot\Api;

class CarpetsBeforeWashingController extends Controller
{
    /**
     * @throws \Telegram\Bot\Exceptions\TelegramSDKException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function index($response, $callback)
    {
        if (isset($response["message"]["caption"])) {
            $username = $response["message"]["chat"]["username"];
            $text = mb_strtolower($response["message"]["caption"]);
            $telegram = new Api(env("TELEGRAM_BOT_TOKEN"));
            $last_key = array_key_last($response["message"]["photo"]);
            $file = $telegram->getFile(['file_id' => $response["message"]["photo"][$last_key]["file_id"]]);
            $file_name = (new RandomString())->generateRandomString(50) . ".jpg";
            $client = new Client();
            $url = "https://api.telegram.org/file/bot" . env("TELEGRAM_BOT_TOKEN") . "/" . $file["file_path"];
            $resource = \GuzzleHttp\Psr7\Utils::tryFopen($_SERVER['DOCUMENT_ROOT'] . '/img/' . $file_name, 'a+');
            $client->request('GET', $url, ['sink' => $resource]);

            $user = DB::table("tg_users")->where("tg_nickname", $username)->first();

            $carpets = new RugsBeforeWashing();
            $carpets->tg_user_id = $user->id;
            $carpets->id_deals = $text;
            $carpets->photo = '/img/' . $file_name;
            $carpets->status = "Not sent";
            $carpets->save();

        } else {
            return "no id";
        }
    }
}
