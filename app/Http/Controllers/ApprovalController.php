<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ApprovalController extends Controller
{
    public function index()
    {
        $users = User::with(['student', 'teacher', 'roles'])
            ->where('is_approved', false)
            ->whereHas('roles', function($q) {
                $q->whereIn('name', ['student', 'teacher']);
            })
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->roles->first()?->name,
                    'student_id' => $user->student?->student_id,
                    'employee_id' => $user->teacher?->employee_id,
                    'created_at' => $user->created_at->toDateTimeString(),
                ];
            });

        return Inertia::render('Admin/Approvals', [
            'pendingUsers' => $users
        ]);
    }

    public function approve(User $user)
    {
        $user->update(['is_approved' => true]);

        return redirect()->back()->with('success', 'User approved successfully.');
    }

    public function reject(User $user)
    {
        // Delete user and related records (cascade handled by DB if set up, or manual)
        $user->delete();

        return redirect()->back()->with('success', 'User rejected and removed.');
    }
}
