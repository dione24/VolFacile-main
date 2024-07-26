<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\CustomVerifyEmail;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        DB::beginTransaction();
        try {
            $user = User::create([
                'firstname' => $request['firstname'],
                'lastname' => $request['lastname'],
                'date_of_birth' => $request['dob'],
                'username' => $request['username'],
                'email' => $request['email'],
                'phone_number' => $request['phone_number'],
                'password' => Hash::make($request['password']),
                'role' => 'ADMIN',
            ]);

            $user->sendEmailVerificationNotification();

            $token = $user->createToken('auth_token')->plainTextToken;
            DB::commit();
            return response()->json(['access_token' => $token, 'user' => $user]);
        } catch (Exception $th) {
            DB::rollBack();
            Log::error($th);
            return response()->json('Une erreur est survenue. Veuillez réessayer.', 500);
        }
    }
    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(
                [
                    'message' => 'Invalid login details',
                ],
                401,
            );
        }

        $user = User::where('email', $request->email)->firstOrFail();

        // Create a token for the authenticated user
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['access_token' => $token, 'user' => $user]);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    public function checkUsernameAvailability(Request $request)
    {
        $isAvailable = !User::where('username', $request->username)->exists();

        return response()->json([
            'available' => $isAvailable,
        ]);
    }

    public function checkEmailAvailability(Request $request)
    {
        $isAvailable = !User::where('email', $request->email)->exists();

        return response()->json([
            'available' => $isAvailable,
        ]);
    }

    public function checkPhoneNumberAvailability(Request $request)
    {
        $isAvailable = !User::where('phone_number', $request->phone_number)->exists();

        return response()->json([
            'available' => $isAvailable,
        ]);
    }
}
