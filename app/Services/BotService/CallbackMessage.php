<?php

namespace App\Services\BotService;

class CallbackMessage
{

    public function CreateFromContext($response)
    {
        try {

            $get_data = explode("*", mb_strtolower($response["callback_query"]['data']));
            switch ($get_data[0]) {
                case "заявки":
                    return (new CheckAuthorize())->HandleMessage($response);
                    break;
                case "авторизовать":
                    return (new Authorize())->HandleMessage($response);
                    break;
                case "группы":
                    return (new Group())->HandleMessage($response);
                    break;
                case "меню":
                    return (new Menu())->HandleMessage($response);
                    break;
                case "полки":
                    return (new Shelves())->HandleMessage($response);
                    break;
                case "от клиента":
                    return (new CarpetsFromClient())->HandleMessage($response);
                    break;
                case "до стирки":
                    return (new CarpetsBeforeWashing())->HandleMessage($response);
                    break;
                case "на стирку":
                    return (new CarpetsForWashing())->HandleMessage($response);
                    break;


            }
        } catch (\Exception $ex) {
            return json_encode("ERR: " . $ex);
        }
    }

}
