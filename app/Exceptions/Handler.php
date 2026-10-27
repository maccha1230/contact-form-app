<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * APIの404エラーをJSON形式で返す
     */
    public function render($request, Throwable $e)
    {
        if (
            $request->is('api/*') &&
            $e instanceof NotFoundHttpException
        ) {
            return response()->json([
                'error' => 'お問い合わせが見つかりませんでした。',
            ], 404);
        }

        return parent::render($request, $e);
    }
}
