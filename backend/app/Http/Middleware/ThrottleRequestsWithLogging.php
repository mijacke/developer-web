<?php

namespace App\Http\Middleware;

use App\Models\RateLimitViolation;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Middleware\ThrottleRequests;

class ThrottleRequestsWithLogging extends ThrottleRequests
{
    /**
     * Handle an incoming request.
     */
    public function handle($request, Closure $next, $maxAttempts = 60, $decayMinutes = 1, $prefix = '')
    {
        $key = $this->resolveRequestSignature($request);

        $maxAttempts = $this->resolveMaxAttempts($request, $maxAttempts);

        if ($this->limiter->tooManyAttempts($key, $maxAttempts)) {
            // Log the violation
            RateLimitViolation::log(
                $request->ip(),
                $request->path(),
                $request->method()
            );

            return $this->buildException($request, $key, $maxAttempts)->render($request);
        }

        $this->limiter->hit($key, $decayMinutes * 60);

        $response = $next($request);

        return $this->addHeaders(
            $response,
            $maxAttempts,
            $this->calculateRemainingAttempts($key, $maxAttempts)
        );
    }
}
