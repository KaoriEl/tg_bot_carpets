<?php

namespace App\Http\Controllers;

use App\Models\CustomerCarpets;
use App\Models\FailedJobsAlbum;
use App\Models\RugsBeforeWashing;
use App\Services\Random\RandomString;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Api;
use Telegram\Bot\Exceptions\TelegramSDKException;

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
            $text = explode(";", mb_strtolower($response["message"]["caption"]));;
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
            $carpets->id_deals = $text[0];
            if (isset($text[1])) {
                $carpets->comment = $text[1];
            }
            $carpets->photo = '/img/' . $file_name;
            $carpets->status = "Not sent";
            $carpets->save();

        } else {
            return "no id";
        }
    }

    /**
     * @throws TelegramSDKException
     * @throws GuzzleException
     */
    public function album($response)
    {
        $username = $response["message"]["chat"]["username"];
        $media_group_id = $response["message"]["media_group_id"];
        $album = DB::table("rugs_before_washing")->where("media_group_id", $media_group_id)->first();
        if (isset($response["message"]["caption"])) {
            $text = explode(";", mb_strtolower($response["message"]["caption"]));;
        } else {
            $text = array(" ", " ");
        }
        $telegram = new Api(env("TELEGRAM_BOT_TOKEN"));
        $last_key = array_key_last($response["message"]["photo"]);
        $file = $telegram->getFile(['file_id' => $response["message"]["photo"][$last_key]["file_id"]]);
        $file_name = (new RandomString())->generateRandomString(50) . ".jpg";
        $fileAlbum = array();
        $fileAlbum[] = '/img/' . $file_name;
        $client = new Client();
        $url = "https://api.telegram.org/file/bot" . env("TELEGRAM_BOT_TOKEN") . "/" . $file["file_path"];
        $resource = \GuzzleHttp\Psr7\Utils::tryFopen($_SERVER['DOCUMENT_ROOT'] . '/img/' . $file_name, 'a+');
        $client->request('GET', $url, ['sink' => $resource]);
        $user = DB::table("tg_users")->where("tg_nickname", $username)->first();
        if (isset($album->media_group_id)) {
            $fileAlbum = json_decode($album->photo, true);
            array_push($fileAlbum, '/img/' . $file_name);
            if (isset($response["message"]["caption"])) {
                DB::table("rugs_before_washing")->where("media_group_id", $media_group_id)->update(['photo' => json_encode($fileAlbum), "id_deals" => $text[0], "comment" => $text[1]]);
            } else {
                DB::table("rugs_before_washing")->where("media_group_id", $media_group_id)->update(['photo' => json_encode($fileAlbum), "id_deals" => $album->id_deals]);
            }
        } else {
            $carpets = new RugsBeforeWashing();
            $carpets->tg_user_id = $user->id;

            //это мой худший проект, реально.
            //Тут я по мега тупому принципу делаю так чтоб небыло дубля сообщения, записываю в бд с failed jobs и сверяю
            if ($text[0] == " ") {
                $params = [
                    'chat_id' => $response["message"]["chat"]["id"],
                    'text' => "<b>Вы не написали id сделки</b>\nНапишите id сделки и прикрепите фото еще раз.",
                    'parse_mode' => 'HTML',
                ];
                $failed_jobs = DB::table("failed_jobs_album")->where("media_group_id", $media_group_id)->first();
                if (!isset($failed_jobs->media_group_id)) {
                    $fail = new FailedJobsAlbum();
                    $fail->media_group_id = $media_group_id;
                    $fail->save();
                    $telegram->sendMessage($params);
                }
                return "fail";

            } else {
                $carpets->id_deals = $text[0];
            }
            $carpets->photo = json_encode($fileAlbum);
            if (isset($text[1])) {
                $carpets->comment = $text[1];
            }
            $carpets->status = "Not sent";
            $carpets->media_group_id = $media_group_id;
            $carpets->save();

            $params = [
                'chat_id' => $response["message"]["chat"]["id"],
                'text' => "Сделка успешно сохранена",
                'parse_mode' => 'HTML',
            ];
            $telegram->sendMessage($params);
        }

    }
}
