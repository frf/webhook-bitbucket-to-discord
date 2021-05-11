<?php

namespace App\Exceptions;

use App\Api\Controllers\ResponseTrait;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    use ResponseTrait;
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

    public function render($request, Throwable $e)
    {
        if ($e instanceof \DomainException) {
            return $this->failure($e);
        }

        if ($e instanceof ModelNotFoundException) {
            throw new ResourceNotFoundException();
        }

        return parent::render($request, $e);
    }

    public function report(Throwable $exception)
    {
        if (
            app()->bound('sentry')
            && $this->shouldReport($exception)
            && !$exception instanceof BaseException
            && env('APP_ENV') == 'production'
        ) {
            app('sentry')->captureException($exception);
        }

        parent::report($exception);
    }
}
