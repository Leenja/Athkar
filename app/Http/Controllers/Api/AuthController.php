<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email:rfc,dns|max:255|unique:users',
            'password' => 'required|string|min:8',
            'timezone' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'timezone' => $request->timezone,
        ]);

        $token = $user->createToken('mobile')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid Credentials',
            ], 401);
        }

        $token = $user->createToken('mobile')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logout']);
    }

    public function profile(Request $request)
    {
        return response()->json($request->user());
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email:rfc,dns|max:255|unique:users,email,' . $user->id,
            'timezone' => 'sometimes|nullable|string|max:100',
        ]);

        $user->update($validated);

        return response()->json($user);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if ($request->new_password !== $request->new_password_confirmation) {
            return response()->json([
                'message' => 'New password and confiration should be the same',
            ], 422);
            }

        $user = $request->user();

        if (! Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Incorrect Password'], 422);
        }

        if (Hash::check($request->new_password, $user->password)) {
            return response()->json([
                'message' => 'New password must be different from the current password',
            ], 422);
        }

        $user->update(['password' => Hash::make($request->new_password)]);

        return response()->json(['message' => 'Password Has Changed Successfully']);
    }

    public function deleteAccount(Request $request)
    {
        $user = $request->user();

        // Delete all tokens (logout from all devices that ha the same token)
        $user->tokens()->delete();

        $user->delete();

        return response()->json([
            'message' => 'Account Has Been Deleted Successfully',
        ]);
    }

    //-------------------------------------------------------------

    //For the website
    public function redirectToGoogle()
    {
        /** @disregard P1013 */
        return Socialite::driver('google')
            ->stateless()
            ->redirect();
    }

    public function handleGoogleCallback()
    {
        /** @disregard P1013 */
        $googleUser = Socialite::driver('google')->stateless()->user();

        $user = $this->findOrCreateGoogleUser($googleUser);

        $token = $user->createToken('web')->plainTextToken;

        // During development, we return it as JSON to easily test it from the browser.
        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }

    //For the mobile application
    public function loginWithGoogleToken(Request $request)
    {
        $request->validate([
            'access_token' => 'required|string',
        ]);

        try {
            /** @disregard P1013 */
            $googleUser = Socialite::driver('google')
                ->stateless()
                ->userFromToken($request->access_token);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Invalid Google code',
            ], 401);
        }

        $user = $this->findOrCreateGoogleUser($googleUser);

        $token = $user->createToken('mobile')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }

    //For both
    private function findOrCreateGoogleUser($googleUser)
    {
        $user = User::where('email', $googleUser->getEmail())->first();

        if (! $user) {
            $user = User::create([
                'name' => $googleUser->getName() ?? $googleUser->getNickname() ?? 'Google User',
                'email' => $googleUser->getEmail(),
                'password' => Hash::make(Str::random(32)), // Random password, will never be used
            ]);
        }

        return $user;
    }
}
