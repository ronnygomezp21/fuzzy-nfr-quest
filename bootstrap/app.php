<?php

use App\Http\Middleware\JwtMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Http\Request;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->appendToGroup('auth:api', JwtMiddleware::class);
        $middleware->prependToGroup('api', \App\Http\Middleware\ForceJsonResponse::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {

        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'data' => null,
                    'message' => 'Ruta no encontrada',
                ], 404);
            }
        });

        $exceptions->render(function (\Illuminate\Validation\ValidationException $e, Request $request) {
            if ($request->is('api/*')) {

                $firstErrorMessage = collect($e->errors())->flatten()->first();

                return response()->json([
                    'data' => null,
                    'message' => $firstErrorMessage,
                ], 422);
            }
        });

        $exceptions->render(function (\Illuminate\Database\QueryException $e, Request $request) {
            if ($request->is('api/*')) {
                $sqlErrorCode = $e->errorInfo[1];
                if ($sqlErrorCode == 1054) {
                    return response()->json([
                        'data' => null,
                        'message' => 'Error en la consulta a la base de datos: Columna no encontrada.',
                        //'error' => $e->getMessage(),
                    ], 400);
                }
                return response()->json([
                    'data' => null,
                    'message' => 'Error en la consulta a la base de datos.',
                    //'error' => $e->getMessage(),
                ], 500);
            }
        });

        $exceptions->render(function (Error $e, Request $request) {
            if ($request->is('api/*')) {
                //if (config('app.debug')) {
                    return response()->json([
                        'data' => null,
                        'message' => $e->getMessage(),
                        // 'file' => $e->getFile(),
                        // 'line' => $e->getLine(),
                    ], 500);
                // } else {
                //     return response()->json([
                //         'data' => null,
                //         'message' => 'Ocurrió un error en el servidor. Por favor, intenta nuevamente más tarde.',
                //     ], 500);
                // }
            }
        });

        // $exceptions->render(function (\Exception $e, Request $request) {
        //     if ($request->is('api/*')) {
        //         if (config('app.debug')) {
        //             return response()->json([
        //                 'data' => null,
        //                 'message' => $e->getMessage(),
        //                 // 'file' => $e->getFile(),
        //                 // 'line' => $e->getLine(),
        //             ], 500);
        //         } else {
        //             return response()->json([
        //                 'data' => null,
        //                 'message' => 'Ocurrió un error en el servidor. Por favor, intenta nuevamente más tarde.',
        //             ], 500);
        //         }
        //     }
        // });
    })->create();
