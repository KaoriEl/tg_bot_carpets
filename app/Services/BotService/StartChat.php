<?php

namespace App\Services\BotService;

use App\Contracts\ChatStrategy;
use App\Http\Controllers\TgUserController;
use App\Models\TgUser;
use Illuminate\Support\Facades\DB;
use Telegram\Bot\Api;

class StartChat implements ChatStrategy
{

    public function HandleMessage($response): array
    {
        $return = (new TgUserController())->index($response);
        if ($return == "Successful addition") {
            return $params = [
                'chat_id' => $response["message"]["chat"]["id"],
                'text' => "Здравствуйте, запрос на авторизацию отправлен, пожалуйста, ожидайте.",
                'parse_mode' => 'HTML',
            ];
        } elseif ($return == "Bad addition") {
            return $params = [
                'chat_id' => $response["message"]["chat"]["id"],
                'text' => "Кажется вы уже есть в базе бота, пожалуйста свяжитесь с администратором для дополнительной информации. Возможно вы еще не авторизированы.",
                'parse_mode' => 'HTML',
            ];
        } elseif ($return == "No Username") {
            return $params = [
                'chat_id' => $response["message"]["chat"]["id"],
                'text' => "Кажется у вас не установлено имя пользователя в телеграмм. \nВы можете установить имя пользователя сделав по инструкции: \n
<b>1. Зайдите в настройки телеграмма</b>\n<b>2. Зайдите в управление профилем</b>\n<b>3. Задайте имя пользователя в третьем пункте со значком @</b>",
                'parse_mode' => 'HTML',
            ];
        }

    }
}
