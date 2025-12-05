<?php

namespace App\Http\Controllers;

use App\Services\StudentService;
use App\Http\Resources\StudentResource;
use Inertia\Inertia;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    protected $service;

    public function __construct(StudentService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Students/Index', [
            'students' => StudentResource::collection($this->service->all(['user', 'classRoom'])),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Students/Create', [
            'classRooms' => \App\Models\ClassRoom::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'dob' => 'required|date',
            'gender' => 'required|string',
            'address' => 'required|string',
            'phone' => 'required|string',
            'class_room_id' => 'required|exists:class_rooms,id',
        ]);

        \Illuminate\Support\Facades\DB::transaction(function () use ($validated) {
            $user = \App\Models\User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => \Illuminate\Support\Facades\Hash::make($validated['password']),
            ]);
            $user->assignRole('student');

            $this->service->create([
                'user_id' => $user->id,
                'class_room_id' => $validated['class_room_id'],
                'dob' => $validated['dob'],
                'gender' => $validated['gender'],
                'address' => $validated['address'],
                'phone' => $validated['phone'],
            ]);
        });

        return redirect()->route('students.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $student = $this->service->find($id)->load(['user', 'classRoom']);
        return Inertia::render('Students/Edit', [
            'student' => $student,
            'classRooms' => \App\Models\ClassRoom::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $student = $this->service->find($id);
        $user = $student->user;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'dob' => 'required|date',
            'gender' => 'required|string',
            'address' => 'required|string',
            'phone' => 'required|string',
            'class_room_id' => 'required|exists:class_rooms,id',
        ]);

        \Illuminate\Support\Facades\DB::transaction(function () use ($user, $student, $validated) {
            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]);

            $this->service->update($student->id, [
                'class_room_id' => $validated['class_room_id'],
                'dob' => $validated['dob'],
                'gender' => $validated['gender'],
                'address' => $validated['address'],
                'phone' => $validated['phone'],
            ]);
        });

        return redirect()->route('students.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->service->delete($id);
        return redirect()->route('students.index');
    }
}
