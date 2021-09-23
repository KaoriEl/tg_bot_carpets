<?php

namespace App\Services\BotService;

class MessageContext
{

    public static function CreateFromContext($response)
    {
        try {
            if (isset($response['callback_query'])) {
                return (new CallbackMessage())->CreateFromContext($response);
            } elseif (isset($response["message"]["text"])) {
                return (new Message())->CreateFromContext($response);
            } elseif (isset($response["message"]["photo"])) {
                ob_start();
                print_r("----------------");
                print_r($response);
                print_r("----------------");
                $debug = ob_get_contents();
                ob_end_clean();
                $fp = fopen('logs.logs', 'a+');
                fwrite($fp, $debug);
                fclose($fp);
                return (new MessageWithPhoto())->CreateFromContext($response);
            }
        } catch (\Exception $ex) {
            ob_start();
            print_r("----------------");
            print_r($ex);
            print_r("----------------");
            $debug = ob_get_contents();
            ob_end_clean();
            $fp = fopen('CreateFromContext.logs', 'a+');
            fwrite($fp, $debug);
            fclose($fp);
        }

    }

}
