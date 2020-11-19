<?php

namespace App\Traits;

Trait ResponsesTraits {
    function responseUnAuthorised () {
        return response()->json(
            [
                'message' => "Could not complete, user not authenticated",
                'HTTPCode' => 401,
                'resolution' => 'Re-Authenticate client'
            
            ] , 401
        );
    }
}


?>