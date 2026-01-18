<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RateLimitViolation extends Model
{
    protected $fillable = [
        'ip_address',
        'route',
        'method',
        'attempts',
    ];

    /**
     * Log a rate limit violation.
     */
    public static function log(string $ip, string $route, string $method = 'POST'): self
    {
        return self::create([
            'ip_address' => $ip,
            'route' => $route,
            'method' => $method,
        ]);
    }
}
