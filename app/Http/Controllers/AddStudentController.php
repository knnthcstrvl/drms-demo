<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\AuditLog;

class AddStudentController extends Controller
{
    public function store(Request $request) {
        try {
            $validatedData = $request->validate([
                'last_name' => 'required|string|max:255',
                'first_name' => 'required|string|max:255',
                'middle_name' => 'nullable|string|max:255',
                'username' => 'required|string|max:255|unique:users,username',
                'email' => 'required|email|max:255|unique:users,email',
                'role' => 'required|string',
                'password' => 'required|string',
                'confirm_password' => 'required|string',
                'access_level' => 'required|string',
            ]);
    
            if ($validatedData['password'] !== $validatedData['confirm_password']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Passwords do not match.',
                ], 422);
            }
    
            $validatedData['password'] = Hash::make($validatedData['password']);
            unset($validatedData['confirm_password']);
    
            $validatedData['status'] = 'Active';
            $validatedData['date_time_created'] = now();
    
            $avatar = Avatar::create(strtoupper($validatedData['full_name']))
                ->setBackground('#3B82F633')
                ->setForeground('#1D4ED8')
                ->getImageObject();
            $avatarPath = storage_path('app/public/avatars/' . $validatedData['username'] . '.jpg');
            $avatar->save($avatarPath, 100);

            $user = User::create($validatedData);

            AuditLog::create([
                'user' => auth()->user()->full_name,
                'date_time' => now(),
                'activity_type' => 'Add',
                'username' => $validatedData['username'],
                'full_name' => $validatedData['full_name'],
            ]);
    
            return response()->json([
                'success' => true,
                'message' => 'User added successfully!',
            ]);
    
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add user.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }    

    public function checkStudentNumber(Request $request)
    {
        dd($request->all());

        $studentNumberExists = Student::where('student_number', $request->student_number)->exists();
        
        return response()->json([
            'student_number_exists' => $studentNumberExists,
            'message' => $studentNumberExists 
                ? 'Student number is already taken.' 
                : ''
        ]);
    }
}
