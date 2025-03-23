<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class DebugAssetMiddleware
{
    public function handle($request, Closure $next)
    {
        if (str_starts_with($request->path(), 'storage/companies/logos')) {
            Log::info('Tentativa de acesso à imagem:', [
                'path' => $request->path(),
                'full_url' => $request->fullUrl(),
                'exists' => file_exists(public_path($request->path()))
            ]);
        }
        return $next($request);
    }
} 