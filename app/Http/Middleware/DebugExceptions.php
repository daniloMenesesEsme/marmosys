<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class DebugExceptions
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            return $next($request);
        } catch (\Throwable $exception) {
            // Registra a exceção de forma detalhada
            Log::error('Exceção capturada pelo middleware de debug: ' . $exception->getMessage(), [
                'uri' => $request->getUri(),
                'method' => $request->method(),
                'trace' => $exception->getTraceAsString(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'request_data' => $request->all()
            ]);
            
            // Repropaga a exceção para que outros handlers possam tratá-la
            throw $exception;
        }
    }
} 