<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class MemberProfileController extends Controller
{
    public function edit(Request $request)
    {
        return view('member.profile', ['user' => $request->user()]);
    }

    public function update(Request $request)
    {
        $user = $request->user();
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->update($data);

        return redirect()->route('member.profile.edit')->with('success', 'Profil berhasil diperbarui.');
    }

    public function password(Request $request)
    {
        return view('member.password');
    }

    public function updatePassword(Request $request)
    {
        $data = $request->validate([
            'current_password' => 'required|string',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        if (! Hash::check($data['current_password'], $request->user()->password)) {
            return back()->withErrors(['current_password' => 'Password lama tidak sesuai.']);
        }

        $request->user()->update(['password' => Hash::make($data['password'])]);

        return redirect()->route('member.password.edit')->with('success', 'Password berhasil diperbarui.');
    }
}
