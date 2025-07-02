<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $r)
    {
        $r->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed',
            'role' => 'required|in:admin,supplier,mitra,customer'
        ]);
        $data = $r->only('name', 'email', 'password', 'role');
        $data['password'] = Hash::make($r->password);
        $data['is_active'] = ($data['role'] === 'customer') ? 1 : 0;
        $user = User::create($data);

        if ($data['role'] === 'supplier') {
            $user->supplier()->create($r->only('nama_supplier', 'perusahaan', 'no_hp', 'alamat', 'foto'));
        } elseif ($data['role'] === 'mitra') {
            $user->mitra()->create($r->only('nama_mitra', 'nama_usaha', 'no_hp', 'alamat', 'foto'));
        } elseif ($data['role'] === 'customer') {
            $user->customer()->create($r->only('nama_customer', 'alamat', 'no_hp', 'foto'));
        }
        return redirect()->route('login')->with('success', 'Registrasi berhasil!');
    }

    public function login(Request $r)
    {
        $credentials = $r->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if ($user->is_active == 0) {
                Auth::logout();
                return back()->withErrors(['Akun Anda belum diverifikasi!']);
            }
            // Redirect sesuai role
            if ($user->role == 'admin')     return redirect()->route('dashboard.admin');
            if ($user->role == 'supplier')  return redirect()->route('supplier.dashboard');
            if ($user->role == 'mitra')     return redirect()->route('home.katalog');
            if ($user->role == 'customer')  return redirect()->route('home.katalog');
            return redirect('/');
        }
        // Jika login gagal
        return back()->withErrors(['Login gagal! Email atau password salah.']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    public function verifikasi()
    {
        // Ambil mitra & supplier yang belum aktif
        $penggunas = \App\Models\User::whereIn('role', ['mitra', 'supplier'])
            ->where('is_active', 0)
            ->get();
        return view('admin.users.verifikasi', compact('penggunas'));
    }

    public function approve($id)
    {
        $user = \App\Models\User::findOrFail($id);
        $user->is_active = 1;
        $user->save();
        return redirect()->route('users.verifikasi')->with('success', 'Akun berhasil diaktifkan!');
    }
}
