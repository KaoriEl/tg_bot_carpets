<?php

namespace App\Services\BotService;

class Message
{

    public function CreateFromContext($response)
    {
        try {
            switch (mb_strtolower($response["message"]["text"])) {
                case "/start":
                    return (new StartChat)->HandleMessage($response);
                    break;
                case "начать работу":
                    return (new StartWork())->HandleMessage($response);
                    break;
                case "☰":
                    return (new Menu())->HandleMessage($response);
                    break;
                case "группы":
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
