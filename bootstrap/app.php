<?php

use App\Exceptions\CustomException;
use App\Http\Middleware\ForceJsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'json.response' => ForceJsonResponse::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // METHOD NOT ALLOWED CUSTOM EXCEPTION
        $exceptions->render(function(MethodNotAllowedHttpException $e, Request $request){
            if($request->is('api/*')){
                $response = [
                    'data' => [],
                    'status' => 'error',
                    'message' => 'Method Not Allowed!',
                ];
                return response()->json($response, 405);
            }
        });
        // VALIDATION CUSTOM EXCEPTION
        $exceptions->render(function(ValidationException $e, Request $request){
            if($request->is('api/*')){
                $response = [
                    'status' => 'error',
                    'message' => implode(
                        ',',
                        collect($e->errors())
                            ->flatten()
                            ->toArray()
                    ),
                    'data' => [
                        'errors' => $e->errors(),
                    ],
                ];
                return response()->json($response, 422);
            }
        });
        // HTTP NOT FOUND CUSTOM EXCEPTION
        $exceptions->render(function(NotFoundHttpException $e, Request $request){
            if($request->is('api/*')){
                $response = [
                    'status' => 'error',
                    'message' => 'Url Not Found!',
                    'data' => [],
                ];
    
                return response()->json($response, 404);
            }
        });
        // HTTP CUSTOM EXCEPTION
        $exceptions->render(function(HttpException $e, Request $request){
            if($request->is('api/*')){
                $response = [
                    'status' => 'error',
                    'message' => $e->getMessage(),
                    'data' => [],
                ];
    
                return response()->json($response, $e->getStatusCode());
            }
        });
        // MODEL/RESOURCE NOT FOUND CUSTOM EXCEPTION
        $exceptions->render(function(ModelNotFoundException $e, Request $request){
            if($request->is('api/*')){
                $response = [
                    'status' => 'error',
                    'message' => 'Resource Not Found',
                    'data' => [],
                ];
    
                return response()->json($response, 404);
            }
        });
        // CUSTOM EXCEPTION
        $exceptions->render(function(CustomException $e, Request $request){
            if($request->is('api/*')){
                $response = [
                    'status' => 'error',
                    'message' => $e->getMessage(),
                    'data' => [],
                ];
    
                return response()->json($response, $e->getCode());
            }
        });
    })->create();
