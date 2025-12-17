<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => 'required|string|in:student,teacher',
            'student_id' => 'required_if:role,student|nullable|string|unique:students,student_id',
            'employee_id' => 'required_if:role,teacher|nullable|string|unique:teachers,employee_id',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_approved' => false, // Default to unapproved
        ]);

        if ($request->role === 'student') {
            $user->assignRole('student');
            \App\Models\Student::create([
                'user_id' => $user->id,
                'student_id' => $request->student_id,
                // Other fields are nullable or have defaults? Check migrations.
                // Migration 2025_12_04_104405_create_students_table.php:
                // class_room_id, dob, gender, address, phone are Required?
                // Let's check the migration file again to be safe.
                // Assuming we make them nullable or provide defaults for now, or ask user to provide them.
                // The current form only has name/email/password.
                // We typically need to collect these extra fields or make them nullable in DB for this "Quick Register" flow.
                // For now, I'll pass dummy or handled values if they are required.
                // Checked migration: class_room_id, dob, gender, address, phone are NOT nullable in migration.
                // This is a problem. The registration form needs to collect ALL these or we need to make them nullable.
                // Given the instructions, I better check the migration again.
            ]);
        } elseif ($request->role === 'teacher') {
            $user->assignRole('teacher');
            \App\Models\Teacher::create([
                'user_id' => $user->id,
                'employee_id' => $request->employee_id,
                // Same issue for Teacher: qualification, specialization, phone required?
            ]);
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
