<?php

namespace App\Traits;
use App\Traits\ResponsesTraits;

trait UserTraits {
    use ResponsesTraits;
    /**
     * Deprecated
     */
    private function authGrant () {
        if (Auth::check()) {
            return true;
        }
        return $this->responseUnAuthorised();
    }
}

?>