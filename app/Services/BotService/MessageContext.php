<?php

namespace App\Services\BotService;

use Illuminate\Support\Facades\Log;
use Mockery\Exception;

class MessageContext
{

    public static function CreateFromContext($response)
    {
        try {
            if (isset($response['callback_query'])) {
                try {
                    return (new CallbackMessage())->CreateFromContext($response);
                } catch (Exception $e) {
                    Log::channel('error-channel')->debug("--------Callback_query Error--------\n" . $e . "\n\n\n");
                }
            } elseif (isset($response["message"]["text"])) {
                try {
                    return (new Message())->CreateFromContext($response);
                } catch (Exception $e) {
                    Log::channel('error-channel')->debug("--------Message_text Error--------\n" . $e . "\n\n\n");
                }
            } elseif (isset($response["message"]["photo"])) {
                try {
                    return (new MessageWithPhoto())->CreateFromContext($response);
                } catch (Exception $e) {
                    Log::channel('error-channel')->debug("--------Message_photo Error--------\n" . $e . "\n\n\n");
                }
            }
        } catch (\Exception $ex) {
            Log::channel('error-channel')->debug("--------MessageContext Error--------\n" . $ex . "\n\n\n");
        }

    }

}
