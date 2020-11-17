<?php

namespace App\Traits;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

trait ModelTraits {

    static function exists ($column, $value) {
        return self::where($column, $value)->count() > 0;
    }

    static function uuidExists ($value) {
        return self::exists('uuid', $value);
    }

    static function generateUuid () {
        $uuid = (String) Str::orderedUuid();
        if (self::uuidExists($uuid)) {
            return self::generateUuid();
        }
        return $uuid;
    }

}


?>