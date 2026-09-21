<?php

namespace App\Http\Controllers\Satgas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
  public function index()
  {
    return view('content.satgas.dashboard');
  }
}
