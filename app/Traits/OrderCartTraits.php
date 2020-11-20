<?php

namespace App\Traits;
use App\Traits\MoneyMaths;

trait OrderCartTraits {
    use MoneyMaths;
    function calculatePrinciple () {
        return $this->multiply($this->quantity, $this->package->price);
    }

    function calculateInterest () {
        $principle = $this->calculatePrinciple();
        $total = $this->multiply($principle, $this->package->rate);
        return $total / 100;
    }
}