<?php

namespace App\Exceptions;

use App\Enums\UserType;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Throwable;

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
     */
    public function register(): void
    {
        // если пользователь не вошёл в систему или не является сотрудником организации,
        // показываем ошибку 404 вместо 403
        $this->renderable(function(AuthorizationException $e, Request $request) {
            if ($request->user() == null)
                return response()->view('errors.404', [], 404);
            if ($request->user()->type == UserType::Client->value)
                return response()->view('errors.404', [], 404);      
        });
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        if ($e instanceof AuthorizationException) {
            if (Auth::user() == null)
                abort(404);
            if (Auth::user()->type == UserType::Client->value)
                abort(404);
        }
        return parent::render($request, $e);
    }
}
