<?php

namespace App\Services\BotService;

use App\Models\FailedJobsAlbum;
use Illuminate\Support\Facades\DB;

class MessageDocument
{
    public function index($telegram,$response){
        if (isset($response["message"]["media_group_id"])) {
            $media_group_id = $response["message"]["media_group_id"];
            $params = [
                'chat_id' => $response["message"]["chat"]["id"],
                'text' => "Не могу скачать фото\nПожалуйста, проверьте отправленное вами сообщение, возможно вы ошиблись.\n",
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
            $msg = [
                'chat_id' => $response["message"]["chat"]["id"],
                'text' => "Не могу скачать фото\nПожалуйста, проверьте отправленное вами сообщение, возможно вы ошиблись.\n",
                'parse_mode' => 'HTML',
            ];
            $telegram->sendMessage($msg);
        }
        return "fail";
    }
}
