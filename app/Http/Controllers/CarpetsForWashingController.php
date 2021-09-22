<?php

namespace App\Http\Controllers;

use App\Models\CarpetsForWashing;
use App\Models\Shelves;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CarpetsForWashingController extends Controller
{
    public function index($response, $callback)
    {

        if ($callback == true) {
            $username = $response["callback_query"]["message"]["chat"]["username"];
            $texts = explode(",", mb_strtolower($response["callback_query"]['data']));
        } else {
            $username = $response["message"]["chat"]["username"];
            $texts = explode(",", mb_strtolower($response["message"]["text"]));
        }

        $user = DB::table("tg_users")->where("tg_nickname", $username)->first();

        foreach ($texts as $text) {
            $carpets = new CarpetsForWashing();
            $carpets->tg_user_id = $user->id;
            $carpets->id_deals = $text;
            $carpets->comment = date("m.d.y");
            $carpets->status = "Not sent";
            $carpets->save();
        }

    }
}
