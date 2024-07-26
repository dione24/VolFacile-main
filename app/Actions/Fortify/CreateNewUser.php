<?php

namespace App\Actions\Fortify;

use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

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
        DB::beginTransaction();
        try {
            $user = User::create([
                'firstname' => $input['firstname'],
                'lastname' => $input['lastname'],
                'username' => $input['username'],
                'phone_number' => $input['phone_number'],
                'email' => $input['email'],
                'password' => Hash::make($input['password']),
                'role' => 'ADMIN',
            ]);
    
            DB::commit();
            return $user;
        } catch (Exception $th) {
            DB::rollBack();
            return response()->json('Une erreur est survenue. Veuillez réessayer.');
        }
    }
}
