<?php

namespace App\Exceptions;

use Exception;
use Throwable;
use Maklad\Permission\Exceptions\UnauthorizedException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
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
    $this->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->wantsJson()) {
            return response()->json(['message' => 'URL not found'], 404);
            }
      });

    $this->renderable(function (AccessDeniedHttpException $e, $request) {
            if ($request->wantsJson()) {
            return response()->json(['message' => 'Access Denied'], 403);
            }
      });

    $this->renderable(function (Exception $e) {
            if ($e instanceof UnauthorizedException) {
            return response()->json(['message' => 'User Access Denied'], 403);
            }
      });

    $this->renderable(function (MethodNotAllowedHttpException $e, $request) {
            if ($request->wantsJson()) {
            return response()->json(['message' => 'Method not allowed'], 405);
            }
      });
    }
}
