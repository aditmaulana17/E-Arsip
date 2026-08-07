<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', 'in:admin,staff'],
            'jabatan' => ['nullable', 'string', 'max:100'],
        ]);

        $data['password'] = Hash::make($data['password']);
        User::create($data);
        ActivityLog::catat('create', 'user', "Menambah pengguna {$data['name']}");

        return back()->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'unique:users,email,' . $user->id],
            'role' => ['required', 'in:admin,staff'],
            'jabatan' => ['nullable', 'string', 'max:100'],
            'is_active' => ['nullable', 'boolean'],
        ]);
        $data['is_active'] = $request->boolean('is_active');

        $user->update($data);
        ActivityLog::catat('update', 'user', "Mengubah pengguna {$user->name}");

        return back()->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $nama = $user->name;
        $user->delete();
        ActivityLog::catat('delete', 'user', "Menghapus pengguna {$nama}");

        return back()->with('success', 'Pengguna berhasil dihapus.');
    }
}
