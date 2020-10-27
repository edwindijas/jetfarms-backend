<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;

use App\Models\Package;
use Illuminate\Http\Request;

class Investments extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    function getAll () {
        return [];
    }
    

}
