<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function show()
    {
        return view('profile.show');
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . auth()->id(),
            'phone' => 'nullable|string|max:20',
            'about_me' => 'nullable|string|max:1000',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
            'remove_profile_image' => 'nullable|boolean',
        ]);

        $user = auth()->user();

        // Prepare user data for update
        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'about_me' => $request->about_me,
        ];

        // Handle password update
        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return redirect()->back()->with('error', 'Current password is incorrect!');
            }
            $userData['password'] = Hash::make($request->new_password);
        }

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $imageName = time() . '_' . $user->id . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/profile_images', $imageName);
            $userData['profile_image'] = 'profile_images/' . $imageName;
        }

        // Handle profile image removal
        if ($request->remove_profile_image) {
            if ($user->profile_image) {
                // Delete the old image file
                Storage::delete('public/' . $user->profile_image);
                $userData['profile_image'] = null;
            }
        }

        // Update user data
        DB::table('users')
            ->where('id', $user->id)
            ->update($userData);

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }
}