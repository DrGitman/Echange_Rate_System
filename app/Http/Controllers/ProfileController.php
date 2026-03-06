<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Show the profile page.
     */
    public function show()
    {
        return view('profile', ['user' => Auth::user()]);
    }

    /**
     * Update general profile info (name, email, dark_mode).
     */
    public function update(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|max:255|unique:users,email,' . $user->id,
            'dark_mode' => 'nullable|in:light,dark',
        ]);

        try {
            $user->update([
                'name'      => $request->name,
                'email'     => $request->email,
                'dark_mode' => $request->dark_mode ?? 'light',
            ]);

            return redirect()->route('profile.show')->with('success', 'Profile updated successfully!');
        } catch (\Exception $e) {
            Log::error('Profile update failed: ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => 'An error occurred while updating your profile.']);
        }
    }

    /**
     * Update profile picture.
     */
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        try {
            /** @var User $user */
            $user = Auth::user();

            // Delete old avatar if it exists
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Store new avatar
            $path = $request->file('avatar')->store('avatars', 'public');
            
            // Save path to DB
            $user->update(['avatar' => $path]);

            return redirect()->route('profile.show')->with('success', 'Profile picture updated!');
        } catch (\Exception $e) {
            Log::error('Avatar upload failed: ' . $e->getMessage());
            return back()->withErrors(['avatar' => 'Failed to upload image. Please try again.']);
        }
    }

    /**
     * Update password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => ['required', 'confirmed', Password::min(6)],
        ]);

        /** @var User $user */
        $user = Auth::user();

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'The current password provided is incorrect.']);
        }

        try {
            // Update to new hashed password
            $user->update([
                'password' => Hash::make($request->password)
            ]);

            return redirect()->route('profile.show')->with('success', 'Password changed successfully!');
        } catch (\Exception $e) {
            Log::error('Password update failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'An error occurred while changing your password.']);
        }
    }
}
