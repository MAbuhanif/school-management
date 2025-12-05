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
    public function index(Request $request)
    {
        $filters = $request->only(['search', 'class_room_id', 'status']);
        $sortBy = $request->input('sort_by', 'created_at');
        $sortDir = $request->input('sort_dir', 'desc');
        $perPage = $request->input('per_page', 10);

        $students = $this->service->getPaginatedList($perPage, $filters, $sortBy, $sortDir);

        return Inertia::render('Students/Index', [
            'students' => StudentResource::collection($students),
            'filters' => $filters,
            'classRooms' => \App\Models\ClassRoom::all(), // For filter dropdown
            'sort_by' => $sortBy,
            'sort_dir' => $sortDir,
        ]);
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids');
        $this->service->bulkDelete($ids);
        return redirect()->back();
    }

    public function export(Request $request)
    {
         // Simple Streamed Download CSV for verification
         $response = new \Symfony\Component\HttpFoundation\StreamedResponse(function() {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Name', 'Email', 'Class', 'DOB', 'Phone']);

            $students = $this->service->all(['user', 'classRoom']); // Export all

            foreach ($students as $student) {
                fputcsv($handle, [
                    $student->user->name,
                    $student->user->email,
                    $student->classRoom ? $student->classRoom->name : 'N/A',
                    $student->dob,
                    $student->phone
                ]);
            }
            fclose($handle);
         });

         $response->headers->set('Content-Type', 'text/csv');
         $response->headers->set('Content-Disposition', 'attachment; filename="students.csv"');

         return $response;
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
            'profile_picture' => 'nullable|image|max:2048', // 2MB Max
        ]);

        $profilePicturePath = null;
        if ($request->hasFile('profile_picture')) {
            $profilePicturePath = $request->file('profile_picture')->store('students', 'public');
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($validated, $profilePicturePath) {
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
                'profile_picture' => $profilePicturePath,
            ]);
        });

        return redirect()->route('students.index')->with('success', 'Student created successfully.');
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
            'profile_picture' => 'nullable|image|max:2048',
        ]);

        $profilePicturePath = $student->profile_picture;
        if ($request->hasFile('profile_picture')) {
            // Optional: Delete old image if exists
            // if ($student->profile_picture) { Storage::disk('public')->delete($student->profile_picture); }
            $profilePicturePath = $request->file('profile_picture')->store('students', 'public');
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($user, $student, $validated, $profilePicturePath) {
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
                'profile_picture' => $profilePicturePath,
            ]);
        });

        return redirect()->route('students.index')->with('success', 'Student updated successfully.');
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
