<?php

namespace App\Http\Controllers;

use App\Models\TgUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TgUserController extends Controller
{
    public function index($response)
    {

        //Фууу, какая гадость
        $first_name = $response["message"]["chat"]["first_name"];
        $last_name = $response["message"]["chat"]["last_name"];
        $chat_id = $response["message"]["chat"]["id"];
        if (isset($response["message"]["chat"]["username"])) {
            $username = $response["message"]["chat"]["username"];
        } else {
            return "No Username";
        }


        $check_uniq = DB::table("tg_users")->where("tg_nickname", $username)->count();

        if ($check_uniq <= 0) {
            $tg_user = new TgUser();
            $tg_user->chat_id = $chat_id;
            $tg_user->name = $first_name . " " . $last_name;
            $tg_user->tg_nickname = $username;
            $tg_user->save();
            return "Successful addition";
        } else {
            return "Bad addition";
        }

    }

    public function UpdateStep($response,$callback,$step){
        if ($callback == true) {
            $username = $response["callback_query"]["message"]["chat"]["username"];
        } else {
            $username = $response["message"]["chat"]["username"];
        }
        DB::table("tg_users")->where("tg_nickname", $username)->update(array('step' => $step));
    }

    public function CheckUser($response, $callback)
    {
        if ($callback == true) {
            $username = $response["callback_query"]["message"]["chat"]["username"];
        } else {
            $username = $response["message"]["chat"]["username"];
        }
        $user = DB::table("tg_users")->where("tg_nickname", $username)->first();
        if ($user != null) {
            return $user;
        } else {
            return "Bad addition";
        }
    }

    public function AuthorizeUser($response, $username)
    {
        DB::table("tg_users")->where("tg_nickname", $username)->update(array('status' => "VERIFIED"));
        return DB::table("tg_users")->where("tg_nickname", $username)->first();
    }

    public function GetUnauthorizedUsers()
    {
        $user = DB::table("tg_users")->where("status", "NOT VERIFIED")->get();

        if (isset($user[0]->name)) {
            return $user;
        } else {
            return "Bad addition";
        }

    }

}
