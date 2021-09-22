<?php

namespace App\Http\Controllers;

use App\Models\Shelves;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShelvesController extends Controller
{
    public function index($response,$callback){

        if ($callback == true) {
            $username = $response["callback_query"]["message"]["chat"]["username"];
            $text = explode("#", mb_strtolower($response["callback_query"]['data']));
        } else {
            $username = $response["message"]["chat"]["username"];
            $text = explode("#", mb_strtolower($response["message"]["text"]));
        }


        $user = DB::table("tg_users")->where("tg_nickname", $username)->first();
        $shelves = new Shelves();
        $shelves->tg_user_id = $user->id;
        $shelves->id_deals = $text[0];
        $shelves->comment = $text[1];
        $shelves->status = "Not sent";
        $shelves->save();
    }
}
