<?php

namespace App\Http\Controllers\authentications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginBasic extends Controller
{
  public function index()
  {
    if (Auth::check()) {
      return $this->redirectBasedOnRole(Auth::user());
    }

    $pageConfigs = ['myLayout' => 'blank'];
    return view('content.authentications.auth-login-basic', ['pageConfigs' => $pageConfigs]);
  }

  public function login(Request $request)
  {
    $loginInput = $request->input('email-username') ?? $request->input('email');

    $request->validate([
      'password' => 'required',
    ], [
      'password.required' => 'Password wajib diisi.',
    ]);

    if (!$loginInput) {
      return back()->withErrors(['login_error' => 'Email atau Username wajib diisi.'])->withInput();
    }

    $fieldType = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';
    $remember = $request->has('remember');

    if (Auth::attempt([$fieldType => $loginInput, 'password' => $request->password], $remember)) {
      $request->session()->regenerate();
      return $this->redirectBasedOnRole(Auth::user());
    }

    return back()->withErrors([
      'login_error' => 'Email/Username atau password yang Anda masukkan salah.',
    ])->withInput();
  }

  public function logout(Request $request)
  {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('pages-home');
  }

  protected function redirectBasedOnRole($user)
  {
    if ($user->role === 'admin') {
      return redirect()->route('admin.dashboard.index');
    } elseif ($user->role === 'satgas') {
      return redirect()->route('satgas.dashboard.index');
    }

    // Default role 'user' mengarah ke halaman beranda
    return redirect()->route('pages-home');
  }
}
