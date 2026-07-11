<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::query()
            ->when($request->filled('role'), function ($query) use ($request) {
                if ($request->role === 'member') {
                    $query->where('is_admin', false);
                } else {
                    $query->where('is_admin', true)->where('admin_role', $request->role);
                }
            })
            ->when($request->filled('q'), function ($query) use ($request) {
                $search = $request->q;
                $query->where(function ($builder) use ($search) {
                    $builder->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
                });
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
            'roles' => User::adminRoles(),
        ]);
    }

    public function create()
    {
        return view('admin.users.form', [
            'user' => new User(['is_admin' => true, 'admin_role' => User::ROLE_FINANCE]),
            'roles' => User::adminRoles(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:160',
            'email' => ['required', 'email', 'max:160', Rule::unique('users', 'email')],
            'admin_role' => ['required', Rule::in(array_keys(User::adminRoles()))],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'email_verified_at' => now(),
            'is_admin' => true,
            'admin_role' => $data['admin_role'],
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User admin berhasil dibuat.');
    }

    public function edit(User $user)
    {
        return view('admin.users.form', [
            'user' => $user,
            'roles' => User::adminRoles(),
        ]);
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:160',
            'email' => ['required', 'email', 'max:160', Rule::unique('users', 'email')->ignore($user->id)],
            'is_admin' => 'nullable|boolean',
            'admin_role' => ['nullable', Rule::in(array_keys(User::adminRoles()))],
        ]);

        $isAdmin = $request->boolean('is_admin');

        if ((int) $user->id === (int) $request->user()->id && ! $isAdmin) {
            return back()->withErrors(['is_admin' => 'Akun yang sedang digunakan tidak bisa diubah menjadi member.'])->withInput();
        }

        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'is_admin' => $isAdmin,
            'admin_role' => $isAdmin ? ($data['admin_role'] ?? User::ROLE_FINANCE) : null,
            'email_verified_at' => $user->email_verified_at ?: now(),
        ]);

        return redirect()->route('admin.users.edit', $user)->with('success', 'Data user berhasil diperbarui.');
    }

    public function resetPassword(Request $request, User $user)
    {
        $data = $request->validate([
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user->update([
            'password' => Hash::make($data['password']),
        ]);

        return redirect()->route('admin.users.edit', $user)->with('success', 'Password user berhasil direset.');
    }
}