<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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
        $this->reportable(function (Throwable $e) {
            // Log detailed error information
            Log::error('Exceção não tratada: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'url' => request()->fullUrl(),
                'method' => request()->method(),
                'input' => request()->except(['password', 'password_confirmation'])
            ]);
        });
        
        $this->renderable(function (Throwable $e, Request $request) {
            if ($request->is('api/*')) {
                // Para requisições da API, retorne um JSON limpo
                $statusCode = $this->getHttpStatusCode($e);
                
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                    'code' => $statusCode
                ], $statusCode);
            }
            
            // Para solicitações web em ambiente de produção (não debug)
            if (!config('app.debug') && !$request->ajax()) {
                $statusCode = $this->getHttpStatusCode($e);
                
                if ($statusCode === 404) {
                    return response()->view('errors.404', ['exception' => $e], 404);
                }
                
                if ($statusCode === 403) {
                    return response()->view('errors.403', ['exception' => $e], 403);
                }
                
                if ($statusCode === 500) {
                    return response()->view('errors.500', ['exception' => $e], 500);
                }
            }
            
            return null; // Deixa o Laravel lidar com o resto
        });
    }
    
    /**
     * Determina o código de status HTTP apropriado para a exceção
     */
    private function getHttpStatusCode(Throwable $e): int
    {
        if ($e instanceof HttpExceptionInterface) {
            return $e->getStatusCode();
        } elseif ($e instanceof ModelNotFoundException) {
            return 404;
        } elseif ($e instanceof AuthorizationException) {
            return 403;
        } elseif ($e instanceof AuthenticationException) {
            return 401;
        } elseif ($e instanceof ValidationException) {
            return 422;
        }
        
        return 500;
    }
}
