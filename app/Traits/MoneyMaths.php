<?php

namespace App\Traits;

trait MoneyMaths {
    function multiply($price, $qty) {
        return (($price * 10) * $qty) / 10;
    }
}
