<?php

use App\Exceptions\ErrorMessageException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Routing\Exceptions\InvalidSignatureException;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function ($router) {
            Route::prefix('api')
                ->middleware('api')
                ->name('api.')
                ->group(base_path('routes/role-permission.php'));
                Route::prefix('api')
                ->middleware('api')
                ->name('api.')
                ->group(base_path('routes/auth.php'));
        }



    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->group('api', [
            \G4T\Swagger\Middleware\SetJsonResponseMiddleware::class,
        ]);
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleCheckMiddleware::class,
            'RPManagement' => \App\Http\Middleware\PermissionCheckMiddleware::class,
            'optional.auth' => \App\Http\Middleware\TokenMiddleware::class,

        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => $e->getMessage(),
                ], 401);
            }
        });


        // Handling AuthenticationException
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'errors' => $e->getMessage(),
                    'message' => 'Unauthorized access. Please check your credentials.',
                    'status' => false,
                ], 401);
            }
        });

        // Handling ValidationException
        $exceptions->render(function (ValidationException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'errors' => $e->errors(),
                    'message' => 'Error: '.$e->validator->errors()->first(),
                    'status' => false,
                ], 422);
            }
        });

        // Handling ModelNotFoundException
        $exceptions->render(function (ModelNotFoundException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'errors' => null,
                    'message' => 'No query results for model ['.$e->getModel().'] with IDs ['.implode(', ', $e->getIds()).']',
                    'status' => false,
                ], 404);
            }
        });

        // Handling InvalidSignatureException
        $exceptions->render(function (InvalidSignatureException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'errors' => null,
                    'message' => 'Invalid signature or the link has expired.',
                    'status' => false,
                ], 403);
            }
        });

        // Handling custom ErrorMessageException
        // $exceptions->render(function (ErrorMessageException $e, Request $request) {

        //     if ($request->is('api/*')) {
        //         return response()->json([
        //             'errors' => null,
        //             'message' => $e->getMessage(),
        //             'status' => false,
        //         ], 400);
        //     }
        // });
    })->create();
