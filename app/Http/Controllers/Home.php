<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Packages;
use Illuminate\Http\Request;

class Home extends Controller
{
    function getData () {
        $investments = new Packages();

        return response()->json(
            ["packages" => $investments->getNew()]
        );
    }
}
