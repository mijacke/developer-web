<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Login and create a new API token.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Nesprávne prihlasovacie údaje.'],
            ]);
        }

        if ($user->isBlocked()) {
            throw ValidationException::withMessages([
                'email' => ['Váš účet bol zablokovaný.'],
            ]);
        }

        if (!$user->isApproved()) {
            throw ValidationException::withMessages([
                'email' => ['Váš účet čaká na schválenie administrátorom.'],
            ]);
        }

        // Update last login
        $user->update(['last_login_at' => now()]);

        // Sanctum SPA login (session-based)
        Auth::guard('web')->login($user);
        $request->session()->regenerate();

        // Log the login
        AuditLog::log('login', $user);

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],
        ]);
    }

    /**
     * Register a new user (pending approval).
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'role' => 'user',
            'is_approved' => false,
        ]);

        // Log the registration
        AuditLog::log('register', $user);

        return response()->json([
            'message' => 'Registrácia úspešná. Váš účet čaká na schválenie administrátorom.',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
        ], 201);
    }

    /**
     * Logout and revoke the current token.
     */
    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();
        
        // Log the logout only if user is authenticated
        if ($user) {
            AuditLog::log('logout', $user);
            
            // Revoke token only if it's a PersonalAccessToken (not TransientToken for SPA)
            $token = $user->currentAccessToken();
            if ($token && method_exists($token, 'delete')) {
                $token->delete();
            }
        }

        // Sanctum SPA logout (session-based)
        Auth::guard('web')->logout();
        
        if ($request->hasSession()) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return response()->json([
            'message' => 'Úspešne odhlásený.',
        ]);
    }

    /**
     * Get the authenticated user.
     */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'is_blocked' => $user->is_blocked,
            'is_approved' => $user->is_approved,
            'last_login_at' => $user->last_login_at,
            'created_at' => $user->created_at,
        ]);
    }

    /**
     * Send password reset link via email.
     * Always returns success message to prevent email enumeration.
     */
    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        // Send reset link (Laravel handles the email sending)
        Password::sendResetLink($request->only('email'));

        // Always return success to prevent email enumeration
        return response()->json([
            'message' => 'Ak existuje účet s touto emailovou adresou, bol na ňu odoslaný odkaz na obnovenie hesla.',
        ]);
    }

    /**
     * Reset password with token.
     */
    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => $password,
                ])->setRememberToken(Str::random(60));

                $user->save();

                AuditLog::log('password_reset', $user);

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json([
                'message' => 'Heslo bolo úspešne zmenené.',
            ]);
        }

        throw ValidationException::withMessages([
            'email' => ['Neplatný alebo expirovaný odkaz na reset hesla.'],
        ]);
    }
}
