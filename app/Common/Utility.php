<?php
namespace App\Common;

class Utility{
    public static function boolean($requestValue)
    {
        return filter_var($requestValue, FILTER_VALIDATE_BOOLEAN);
    }
}
