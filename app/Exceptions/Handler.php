<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
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
        // EventNotFoundException handler
        $this->renderable(function (EventNotFoundException $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 'error',
            ], Response::HTTP_NOT_FOUND);
        });

        // EventCapacityException handler
        $this->renderable(function (EventCapacityException $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 'error',
            ], Response::HTTP_CONFLICT);
        });

        // DuplicateBookingException handler
        $this->renderable(function (DuplicateBookingException $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 'error',
            ], Response::HTTP_CONFLICT);
        });
    }
}
