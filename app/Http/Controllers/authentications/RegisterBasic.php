<?php

namespace App\Http\Controllers\authentications;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterBasic extends Controller
{
  public function index()
  {
    if (Auth::check()) {
      return redirect()->route('pages-home');
    }

    $pageConfigs = ['myLayout' => 'blank'];
    return view('content.authentications.auth-register-basic', ['pageConfigs' => $pageConfigs]);
  }

  public function register(Request $request)
  {
    $name = $request->input('name') ?? $request->input('username');

    $request->validate([
      'email' => 'required|string|email|max:255|unique:users,email',
      'password' => 'required|string|min:6',
    ], [
      'email.required' => 'Email wajib diisi.',
      'email.email' => 'Format email tidak valid.',
      'email.unique' => 'Email sudah terdaftar.',
      'password.required' => 'Password wajib diisi.',
      'password.min' => 'Password minimal 6 karakter.',
    ]);

    if (!$name) {
      return back()->withErrors(['name' => 'Nama/Username wajib diisi.'])->withInput();
    }

    $user = User::create([
      'name' => $name,
      'email' => $request->email,
      'password' => Hash::make($request->password),
      'role' => 'user', // Otomatis role user saat pendaftaran
    ]);

    Auth::login($user);

    return redirect()->route('pages-home')->with('success', 'Registrasi berhasil! Selamat datang.');
  }
}
