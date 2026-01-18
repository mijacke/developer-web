<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use App\Models\Project;
use App\Models\Locality;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Get dashboard statistics.
     */
    public function stats(): JsonResponse
    {
        $totalUsers = User::count();
        $totalAdmins = User::where('role', 'admin')->count();
        $blockedUsers = User::where('is_blocked', true)->count();
        $pendingUsers = User::where('is_approved', false)->count();
        $activeToday = User::whereDate('last_login_at', today())->count();
        
        $totalProjects = Project::count();
        $totalLocalities = Locality::count();
        
        $recentAuditLogs = AuditLog::with('user')
            ->latest()
            ->take(10)
            ->get()
            ->map(fn ($log) => [
                'id' => $log->id,
                'action' => $log->action,
                'model_type' => $log->model_type ? class_basename($log->model_type) : 'System',
                'model_id' => $log->model_id,
                'user' => $log->user?->name ?? 'System',
                'created_at' => $log->created_at->toIso8601String(),
            ]);

        return response()->json([
            'users' => [
                'total' => $totalUsers,
                'admins' => $totalAdmins,
                'blocked' => $blockedUsers,
                'pending' => $pendingUsers,
                'active_today' => $activeToday,
            ],
            'content' => [
                'projects' => $totalProjects,
                'localities' => $totalLocalities,
            ],
            'recent_activity' => $recentAuditLogs,
        ]);
    }

    /**
     * List all users.
     */
    public function users(Request $request): JsonResponse
    {
        $users = User::query()
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->when($request->filter === 'pending', fn ($q) => $q->where('is_approved', false))
            ->when($request->filter === 'blocked', fn ($q) => $q->where('is_blocked', true))
            ->when($request->filter === 'admins', fn ($q) => $q->where('role', 'admin'))
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json([
            'data' => $users->items(),
            'meta' => [
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
            ],
        ]);
    }

    /**
     * Approve a user.
     */
    public function approveUser(int $id): JsonResponse
    {
        $user = User::findOrFail($id);

        $oldValues = ['is_approved' => $user->is_approved];
        $user->update(['is_approved' => true]);

        AuditLog::log('approve_user', $user, $oldValues, ['is_approved' => true]);

        return response()->json([
            'message' => 'User has been approved.',
            'user' => $user->fresh(),
        ]);
    }

    /**
     * Reject (delete) a pending user.
     */
    public function rejectUser(int $id): JsonResponse
    {
        $user = User::findOrFail($id);

        if ($user->is_approved) {
            return response()->json([
                'message' => 'Cannot reject an already approved user. Use delete instead.',
            ], 422);
        }

        $userData = $user->toArray();
        $user->delete();

        AuditLog::logAction('reject_user', User::class, $id, $userData, null);

        return response()->json([
            'message' => 'Pending user has been rejected.',
        ]);
    }

    /**
     * Promote a user to admin.
     */
    public function promoteUser(int $id): JsonResponse
    {
        $user = User::findOrFail($id);

        if ($user->role === 'admin') {
            return response()->json([
                'message' => 'User is already an admin.',
            ], 422);
        }

        $oldValues = ['role' => $user->role];
        $user->update(['role' => 'admin']);

        AuditLog::log('promote_user', $user, $oldValues, ['role' => 'admin']);

        return response()->json([
            'message' => 'User has been promoted to admin.',
            'user' => $user->fresh(),
        ]);
    }

    /**
     * Demote an admin to user.
     */
    public function demoteUser(int $id): JsonResponse
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return response()->json([
                'message' => 'You cannot demote yourself.',
            ], 422);
        }

        if ($user->role !== 'admin') {
            return response()->json([
                'message' => 'User is not an admin.',
            ], 422);
        }

        $oldValues = ['role' => $user->role];
        $user->update(['role' => 'user']);

        AuditLog::log('demote_user', $user, $oldValues, ['role' => 'user']);

        return response()->json([
            'message' => 'Admin has been demoted to user.',
            'user' => $user->fresh(),
        ]);
    }

    /**
     * Block a user.
     */
    public function blockUser(int $id): JsonResponse
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return response()->json([
                'message' => 'You cannot block yourself.',
            ], 422);
        }

        $oldValues = ['is_blocked' => $user->is_blocked];
        $user->update(['is_blocked' => true]);

        // Revoke all user tokens
        $user->tokens()->delete();

        AuditLog::log('block_user', $user, $oldValues, ['is_blocked' => true]);

        return response()->json([
            'message' => 'User has been blocked.',
            'user' => $user->fresh(),
        ]);
    }

    /**
     * Unblock a user.
     */
    public function unblockUser(int $id): JsonResponse
    {
        $user = User::findOrFail($id);

        $oldValues = ['is_blocked' => $user->is_blocked];
        $user->update(['is_blocked' => false]);

        AuditLog::log('unblock_user', $user, $oldValues, ['is_blocked' => false]);

        return response()->json([
            'message' => 'User has been unblocked.',
            'user' => $user->fresh(),
        ]);
    }

    /**
     * Delete a user.
     */
    public function deleteUser(int $id): JsonResponse
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return response()->json([
                'message' => 'You cannot delete yourself.',
            ], 422);
        }

        $userData = $user->toArray();

        // Revoke all tokens and delete
        $user->tokens()->delete();
        $user->delete();

        AuditLog::logAction('delete_user', User::class, $id, $userData, null);

        return response()->json([
            'message' => 'User has been deleted.',
        ]);
    }

    /**
     * Get audit logs.
     */
    public function auditLogs(Request $request): JsonResponse
    {
        $logs = AuditLog::with('user')
            ->when($request->action, fn ($q, $action) => $q->where('action', $action))
            ->when($request->user_id, fn ($q, $userId) => $q->where('user_id', $userId))
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return response()->json([
            'data' => $logs->items(),
            'meta' => [
                'current_page' => $logs->currentPage(),
                'last_page' => $logs->lastPage(),
                'per_page' => $logs->perPage(),
                'total' => $logs->total(),
            ],
        ]);
    }

    /**
     * Get contact messages.
     */
    public function contactMessages(Request $request): JsonResponse
    {
        $messages = \App\Models\ContactMessage::query()
            ->when($request->filter === 'unread', fn ($q) => $q->where('is_read', false))
            ->when($request->search, function ($query, $search) {
                $query->where('email', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json([
            'data' => $messages->items(),
            'meta' => [
                'current_page' => $messages->currentPage(),
                'last_page' => $messages->lastPage(),
                'per_page' => $messages->perPage(),
                'total' => $messages->total(),
                'unread' => \App\Models\ContactMessage::unread()->count(),
            ],
        ]);
    }

    /**
     * Mark a contact message as read.
     */
    public function markMessageRead(int $id): JsonResponse
    {
        $message = \App\Models\ContactMessage::findOrFail($id);
        $message->update(['is_read' => true]);

        return response()->json([
            'message' => 'Message marked as read.',
            'data' => $message->fresh(),
        ]);
    }

    /**
     * Delete a contact message.
     */
    public function deleteMessage(int $id): JsonResponse
    {
        $message = \App\Models\ContactMessage::findOrFail($id);
        $message->delete();

        return response()->json([
            'message' => 'Message deleted.',
        ]);
    }

    /**
     * Get contact form statistics for the chart.
     */
    public function contactStats(): JsonResponse
    {
        $startDate = now()->subDays(30)->startOfDay();
        
        // Daily message counts for last 30 days
        $dailyCounts = \App\Models\ContactMessage::where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date')
            ->toArray();

        // Fill in missing days with 0
        $chartData = [];
        for ($i = 30; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $chartData[] = [
                'date' => $date,
                'label' => now()->subDays($i)->format('d.m'),
                'count' => $dailyCounts[$date] ?? 0,
            ];
        }

        // Rate limit violations for last 30 days
        $violations = \App\Models\RateLimitViolation::where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count, ip_address')
            ->groupBy('date', 'ip_address')
            ->orderBy('date', 'desc')
            ->take(50)
            ->get();

        // Top spam IPs
        $topSpammers = \App\Models\RateLimitViolation::where('created_at', '>=', $startDate)
            ->selectRaw('ip_address, COUNT(*) as violation_count')
            ->groupBy('ip_address')
            ->orderBy('violation_count', 'desc')
            ->take(10)
            ->get();

        return response()->json([
            'chart' => $chartData,
            'violations' => [
                'total' => \App\Models\RateLimitViolation::where('created_at', '>=', $startDate)->count(),
                'today' => \App\Models\RateLimitViolation::whereDate('created_at', today())->count(),
                'recent' => $violations,
                'top_spammers' => $topSpammers,
            ],
            'summary' => [
                'total_messages' => \App\Models\ContactMessage::count(),
                'unread_messages' => \App\Models\ContactMessage::unread()->count(),
                'messages_today' => \App\Models\ContactMessage::whereDate('created_at', today())->count(),
                'messages_this_month' => \App\Models\ContactMessage::where('created_at', '>=', now()->startOfMonth())->count(),
            ],
        ]);
    }
}

