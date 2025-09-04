<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\AuditLog;
use App\Models\DataFeed;
use Laravolt\Avatar\Facade as Avatar;

class AccessControlController extends Controller
{
    public function index() {
        $dataFeed = new DataFeed();
        return view('pages.access_control.add-user', compact('dataFeed'));
    }

    public function store(Request $request) {
        try {
            $validatedData = $request->validate([
                'full_name' => 'required|string|max:255',
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
                ->setBackground('#EBE8FF')
                ->setForeground('#5C297C')
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

            session()->flash('success_notification', 'User added successfully.');
    
            return response()->json([
                'success' => true,
                'message' => 'User added successfully!',
            ]);
    
        } catch (\Exception $e) {
            session()->flash('error_notification', 'Failed to add user.');
            return response()->json([
                'success' => false,
                'message' => 'Failed to add user.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }    

    public function checkUsernameEmail(Request $request) {
        $usernameExists = User::where('username', $request->username)->exists();
        $emailExists = User::where('email', $request->email)->exists();
    
        return response()->json([
            'exists' => $usernameExists || $emailExists,
            'username_exists' => $usernameExists,
            'email_exists' => $emailExists,
            'message' => $usernameExists && $emailExists 
                ? 'Username and email are already taken.' 
                : ($usernameExists 
                    ? 'Username is already taken.' 
                    : ($emailExists ? 'Email is already registered.' : ''))
        ]);
    }

    public function manageUsers() {
        $dataFeed = new DataFeed();
        return view('pages.access_control.manage-users', [
            'dataFeed' => new DataFeed(),
            'username' => null,
            'email' => null
        ]);
        
    }

    public function edit($selected = null) {
        $username = User::where('username', $selected)->first();

        if (!$username) {
            return view('pages.access_control.manage-users', [
                'username' => null,
                'email' => null
            ]);
        }

        return view('pages.access_control.manage-users', [
            'username' => $username,
            'email' => null
        ]);
    }

    public function updateUserDetails(Request $request, $username) {
        try {
            $user = User::where('username', $username)->firstOrFail();

            $validatedData = $request->validate([
                'full_name' => 'required|string|max:255',
                'role' => 'required|string',
                'access_level' => 'required|string',
                'status' => 'required|string',
            ]);

            $validatedData['date_time_updated'] = now();

            // Update user details
            $user->update($validatedData);

            // Log the update in AuditLog
            AuditLog::create([
                'user' => auth()->user()->full_name, // The admin who updated the user
                'date_time' => now(),
                'activity_type' => 'Edit',
                'username' => $user->username, // Keep original username
                'full_name' => $validatedData['full_name'],
            ]);

            session()->flash('success_notification', 'User details updated successfully.');

            return response()->json([
                'success' => true,
                'message' => 'User details updated successfully!',
            ]);

        } catch (\Exception $e) {
            session()->flash('error_notification', 'Failed to update user details.');
            return response()->json([
                'success' => false,
                'message' => 'Failed to update user details.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function reset($selected = null) {
        $email = User::where('email', $selected)->first();

        if (!$email) {
            return view('pages.access_control.manage-users', [
                'username' => null,
                'email' => null
            ]);
        }

        return view('pages.access_control.manage-users', [
            'username' => null,
            'email' => $email
        ]);
    }

    public function updateUserPassword(Request $request, $email) {
        try {
            $user = User::where('email', $email)->firstOrFail();

            $validatedData = $request->validate([
                'password' => 'required|string',
                'confirm_password' => 'required|string',
            ]);

            if ($validatedData['password'] !== $validatedData['confirm_password']) {
                session()->flash('error_notification', 'Passwords do not match.');
                return response()->json([
                    'success' => false,
                    'message' => 'Passwords do not match.',
                ], 422);
            }

            $user->update([
                'password' => Hash::make($validatedData['password']),
                'date_time_updated' => now(),
            ]);

            // Log the password reset in AuditLog
            AuditLog::create([
                'user' => auth()->user()->full_name, // The admin who reset the password
                'date_time' => now(),
                'activity_type' => 'Reset',
                'username' => $user->username, // Retrieve from user model
                'full_name' => $user->full_name, // Retrieve from user model
            ]);

            session()->flash('success_notification', 'Password reset successfully.');

            return response()->json([
                'success' => true,
                'message' => 'Password reset successfully!',
            ]);

        } catch (\Exception $e) {
            session()->flash('error_notification', 'Failed to reset password.');
            return response()->json([
                'success' => false,
                'message' => 'Failed to reset password.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}