<?php

namespace App\Services\BotService;

use Illuminate\Support\Facades\Log;

class CallbackMessage
{

    public function CreateFromContext($response)
    {
        try {

            $get_data = explode("*", mb_strtolower($response["callback_query"]['data']));
            switch ($get_data[0]) {
                case "заявки":
                    Log::channel('debug-channel')->debug("--------Callback_query - Заявки --------\n" . $response . "\n\n\n");
                    return (new CheckAuthorize())->HandleMessage($response);
                    break;
                case "авторизовать":
                    Log::channel('debug-channel')->debug("--------Callback_query - авторизовать --------\n" . $response . "\n\n\n");
                    return (new Authorize())->HandleMessage($response);
                    break;
                case "группы":
                    Log::channel('debug-channel')->debug("--------Callback_query - группы --------\n" . $response . "\n\n\n");
                    return (new Group())->HandleMessage($response);
                    break;
                case "меню":
                    Log::channel('debug-channel')->debug("--------Callback_query - меню --------\n" . $response . "\n\n\n");
                    return (new Menu())->HandleMessage($response);
                    break;
                case "полки":
                    Log::channel('debug-channel')->debug("--------Callback_query - полки --------\n" . $response . "\n\n\n");
                    return (new Shelves())->HandleMessage($response);
                    break;
                case "от клиента":
                    Log::channel('debug-channel')->debug("--------Callback_query - от клиента --------\n" . $response . "\n\n\n");
                    return (new CarpetsFromClient())->HandleMessage($response);
                    break;
                case "до стирки":
                    Log::channel('debug-channel')->debug("--------Callback_query - до стирки --------\n" . $response . "\n\n\n");
                    return (new CarpetsBeforeWashing())->HandleMessage($response);
                    break;
                case "на стирку":
                    Log::channel('debug-channel')->debug("--------Callback_query - на стирку --------\n" . $response . "\n\n\n");
                    return (new CarpetsForWashing())->HandleMessage($response);
                    break;

                default:
                    return $params = [
                        'chat_id' => $response["callback_query"]["message"]["chat"]["id"],
                        'text' => "Этот функционал еще в разработке",
                        'parse_mode' => 'HTML',
                    ];


            }
        } catch (\Exception $ex) {
            return json_encode("ERR: " . $ex);
        }
    }

}
