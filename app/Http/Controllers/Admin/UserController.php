<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users',
            'nama_lengkap' => 'required',
            'password' => 'required|min:6',
            'role' => 'required|in:produksi,gudang,pengadaan', // [DIPERBAIKI] HILANGKAN ADMIN
        ]);

        User::create([
            'username' => $request->username,
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_active' => true,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan!');
    }

    public function edit(User $user)
    {
        // [DIPERBAIKI] Jika user admin, redirect ke halaman index
        if ($user->role == 'admin') {
            return redirect()->route('admin.users.index')->with('error', 'User Admin tidak dapat diedit!');
        }

        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        // [DIPERBAIKI] Jika user admin, redirect ke halaman index
        if ($user->role == 'admin') {
            return redirect()->route('admin.users.index')->with('error', 'User Admin tidak dapat diupdate!');
        }

        $request->validate([
            'username' => 'required|unique:users,username,' . $user->id,
            'nama_lengkap' => 'required',
            'role' => 'required|in:produksi,gudang,pengadaan', // [DIPERBAIKI] HILANGKAN ADMIN
            'is_active' => 'required|boolean',
        ]);

        $data = [
            'username' => $request->username,
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
            'role' => $request->role,
            'is_active' => $request->is_active,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diupdate!');
    }

    public function destroy(User $user)
    {
        // [DIPERBAIKI] Cegah penghapusan admin
        if ($user->role == 'admin') {
            return redirect()->route('admin.users.index')->with('error', 'User Admin tidak dapat dihapus!');
        }

        // Cegah penghapusan admin terakhir (jika ada admin yang tersisa)
        if ($user->role === 'admin' && User::where('role', 'admin')->count() <= 1) {
            return back()->with('error', 'Tidak dapat menghapus admin terakhir!');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus!');
    }
}