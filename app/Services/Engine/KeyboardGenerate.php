<?php

namespace App\Services\Engine;

class KeyboardGenerate
{
    private array $keyboard;

    /**
     * @param $keyboard
     */
    public function __construct($keyboard)
    {
        $this->keyboard = $keyboard;
    }

    /**
     * @param array $data The delimiter for text and callback data is: "," Callback data must be written with a lowercase letter. For example $data = ["text_button,callback_data,text_button,callback_data"];
     * @param string $options  = "base" use a default keyboard, "new" generate a full new keyboard
     * @return array|string[]|void
     */
    public function generate(array $data, string $options = "base") {
        switch ($options) {
            case "base":
                return $this->new_base_keyboard($data);
                break;
            case "new":
                return $this->new_keyboard($data);
                break;
        }
    }

    /**
     * generate new keyboard based on the base provided in the class
     * @param $data
     * @return array
     */
    public function new_base_keyboard($data){
        $keyboard = $this->keyboard;
        foreach ($data as $pair){
            $torn_pair = $this->regex_data($pair);
            $keyboard['inline_keyboard'][][] =  ['text' => $torn_pair[0], 'callback_data' => $torn_pair[1]];
        }
        return $keyboard;
    }

    /**
     * Separate text and callback data
     * @param $pair
     * @return false|string[]
     */
    public function regex_data($pair) {
        return explode(',', $pair);
    }

    /**
     * generate a completely new keyboard not based on the base provided in the class
     * @param $data
     * @return array|string[]
     */
    public function new_keyboard($data): array
    {
        $keyboard = ['inline_keyboard'];
        foreach ($data as $pair){
            $torn_pair = $this->regex_data($pair);
            $keyboard['inline_keyboard'][][] =  ['text' => $torn_pair[0], 'callback_data' => $torn_pair[1]];
        }
        return $keyboard;

    }

    public function default_keyboard(): array
    {
        return $this->keyboard;

    }

}
