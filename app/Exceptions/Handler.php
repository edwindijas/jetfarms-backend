<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /*public function render ($request, Exception $exception) {
        return 'mbola';
        /*
        if ($exception instanceof MethodNotAllowedHttpEception) {
            return response()->json([
                'message' => 'An error occured while processing request',
                'status' => false],
                500
            );
        };

        return response()->json([
            'message' => 'Page Not Found',
            'status' => false],
            404
        );
    }*/
}
