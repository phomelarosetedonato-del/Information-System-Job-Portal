<?php
use App\Http\Middleware\Localization;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\PwdMiddleware;
use App\Http\Middleware\EmployerMiddleware;
use App\Http\Middleware\VerifiedEmployer;
use App\Http\Middleware\PendingEmployerVerification;
use App\Http\Middleware\CheckPwdProfileComplete;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Register middleware aliases
        $middleware->alias([
            'admin' => AdminMiddleware::class,
            'pwd' => PwdMiddleware::class,
            'employer' => EmployerMiddleware::class,
            'verified.employer' => VerifiedEmployer::class,
            'pending.employer.verification' => PendingEmployerVerification::class,
            'localization' => Localization::class,
            'pwd.profile.complete' => CheckPwdProfileComplete::class,
        ]);

        // Add localization to web middleware group
        $middleware->web(append: [
            Localization::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
