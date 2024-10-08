<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Throwable;

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
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $e
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $e)
    {
        if ($e instanceof ModelNotFoundException) {
            return response()->json([
                'status' => false,
                'message' => 'Not Found'
            ])->setStatusCode(404);
        } elseif ($e instanceof ValidationException) {
            return response()->json([
                'status' => false,
                'message' => $e->errors()[array_key_first($e->errors())][0]
            ])->setStatusCode(422);
        } elseif ($e instanceof NotFoundHttpException) {
            return response()->json([
                'status' => false,
                'message' => "Not Found"
            ])->setStatusCode(404);
        } elseif ($e instanceof MethodNotAllowedException || $e instanceof MethodNotAllowedHttpException) {
            return response()->json([
                'status' => false,
                'message' => "Method Not Allowed"
            ])->setStatusCode(405);
        } elseif ($e instanceof \Exception) {
            return response()->json([
                'status' => false,
                'message' => 'Internal Server Error'
            ])->setStatusCode(500);
        }

        return parent::render($request, $e);
    }
}
