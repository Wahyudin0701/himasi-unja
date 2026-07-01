<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('web')
                ->group(__DIR__.'/../routes/kepengurusan.php');
                
            Route::middleware('web')
                ->group(__DIR__.'/../routes/kepanitiaan.php');
                
            Route::middleware('web')
                ->group(__DIR__.'/../routes/super_admin.php');
        }
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\GlobalRole::class,
            'committee.role' => \App\Http\Middleware\CheckCommitteeRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
