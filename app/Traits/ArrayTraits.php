<?php

namespace App\Traits;

trait ArrayTraits {
    function hashBy(&$items, $key) {
        $arr = [];
        foreach($items as $item) {
            $arr[$item[$key]] = $item;
        };

        return $arr;
    }
}
