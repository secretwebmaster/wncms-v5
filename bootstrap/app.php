<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->validateCsrfTokens(except: [
            'install/*',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {

        // avoid infinite loop
        if(url()->previous() == url()->current()){
            return;
        }

        $exceptions->render(function (Illuminate\Database\QueryException $e) {
            logger()->error($e);

            if($e->getCode() == '42S02'){
                return back()->withErrors([
                    'message' => $e->getMessage(),
                ]);
            }
        });

        // $exceptions->render(function (MethodNotAllowedHttpException $e) {
        //     logger()->error($e);
        //     return response()->view('errors.405');
        // });
    })
    ->create();