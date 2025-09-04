<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;
use Laravolt\Avatar\Facade as Avatar;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'full_name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255'],
            'email' => ['string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
        ])->validate();
        
        // Validator::make($input, [
        //     'full_name' => ['required', 'string', 'max:255'],
        //     'username' => ['required', 'string', 'max:255'],
        //     'email' => ['string', 'email', 'max:255', 'unique:users'],
        //     'password' => $this->passwordRules(),
        //     'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        // ])->validate();

        $avatarPath = 'avatars/' . $input['username'] . '.jpg';

        $avatar = Avatar::create($input['full_name'])
            ->setBackground('#F0D9FF')
            ->setForeground('#5C297C')
            ->save(storage_path('app/public/' . $avatarPath), 100);
        
        return User::create([
            'full_name' => $input['full_name'],
            'username' => $input['username'],
            'email' => $input['email'],
            'role' => 'Admin',
            'password' => Hash::make($input['password']),
            'access_level' => 'View,Download,Print',
            'status' => 'Active',
            'profile_photo_path' => $avatarPath,
            'date_time_created' => now(),
        ]);
    }
}
