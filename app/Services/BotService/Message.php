<?php

namespace App\Services\BotService;

use Illuminate\Support\Facades\Log;

class Message
{

    public function CreateFromContext($response)
    {
        try {
            switch (mb_strtolower($response["message"]["text"])) {
                case "/start":
                    Log::channel('debug-channel')->debug("--------Message_Text - /start --------\n" . $response . "\n\n\n");
                    return (new StartChat)->HandleMessage($response);
                    break;
                case "начать работу":
                    Log::channel('debug-channel')->debug("--------Message_Text - начать работу --------\n" . $response . "\n\n\n");
                    return (new StartWork())->HandleMessage($response);
                    break;
                case "☰":
                    Log::channel('debug-channel')->debug("--------Message_Text - ☰ --------\n" . $response . "\n\n\n");
                    return (new Menu())->HandleMessage($response);
                    break;
                case "группы":
                    Log::channel('debug-channel')->debug("--------Message_Text - группы --------\n" . $response . "\n\n\n");
                    return (new GroupBack())->HandleMessage($response);
                    break;
                default:
                    return (new DefaultMessage())->HandleMessage($response);
            }
        } catch (\Exception $ex) {
            return json_encode("ERR: " . $ex);
        }

    }

}
