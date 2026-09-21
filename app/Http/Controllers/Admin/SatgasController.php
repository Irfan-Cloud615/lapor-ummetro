<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SatgasController extends Controller
{
  public function index()
  {
    return view('content.admin.satgas');
  }

  public function datatable(Request $request)
  {
    $draw   = $request->get('draw', 1);
    $search = $request->input('search.value', '');

    $query = User::where('role', 'satgas');

    if (!empty($search)) {
      $query->where(function ($q) use ($search) {
        $q->where('name', 'like', "%{$search}%")
          ->orWhere('email', 'like', "%{$search}%");
      });
    }

    $total    = User::where('role', 'satgas')->count();
    $filtered = $query->count();

    $start  = (int) $request->input('start', 0);
    $length = (int) $request->input('length', 10);

    $orderColumnIndex = $request->input('order.0.column', 1);
    $orderDir = $request->input('order.0.dir', 'asc');

    $columnsMap = [
      0 => 'id',
      1 => 'name',
      2 => 'email',
      3 => 'created_at',
    ];

    $orderColumn = $columnsMap[$orderColumnIndex] ?? 'name';
    $query->orderBy($orderColumn, $orderDir)->orderBy('id', 'asc');

    if ($length !== -1) {
      $query->skip($start)->take($length);
    }

    $rows = $query->get();

    $data = [];
    foreach ($rows as $i => $row) {
      $data[] = [
        'no'         => $start + $i + 1,
        'id'         => $row->id,
        'name'       => $row->name,
        'email'      => $row->email,
        'created_at' => $row->created_at ? $row->created_at->format('d/m/Y') : '-',
      ];
    }

    return response()->json([
      'draw'            => $draw,
      'recordsTotal'    => $total,
      'recordsFiltered' => $filtered,
      'data'            => $data,
    ]);
  }

  public function store(Request $request)
  {
    $request->validate([
      'name'     => 'required|string|max:255',
      'email'    => 'required|email|unique:users,email',
      'password' => 'required|string|min:6',
    ], [
      'name.required'     => 'Nama wajib diisi.',
      'email.required'    => 'Email wajib diisi.',
      'email.unique'      => 'Email sudah terdaftar.',
      'password.required' => 'Password wajib diisi.',
      'password.min'      => 'Password minimal 6 karakter.',
    ]);

    User::create([
      'name'     => $request->name,
      'email'    => $request->email,
      'password' => Hash::make($request->password),
      'role'     => 'satgas',
    ]);

    return response()->json(['success' => true, 'message' => 'Satgas berhasil ditambahkan.']);
  }

  public function update(Request $request, $id)
  {
    $satgas = User::where('id', $id)->where('role', 'satgas')->firstOrFail();

    $request->validate([
      'name'     => 'required|string|max:255',
      'email'    => 'required|email|unique:users,email,' . $id,
      'password' => 'nullable|string|min:6',
    ], [
      'name.required'  => 'Nama wajib diisi.',
      'email.required' => 'Email wajib diisi.',
      'email.unique'   => 'Email sudah digunakan.',
      'password.min'   => 'Password minimal 6 karakter.',
    ]);

    $satgas->name  = $request->name;
    $satgas->email = $request->email;

    if (!empty($request->password)) {
      $satgas->password = Hash::make($request->password);
    }

    $satgas->save();

    return response()->json(['success' => true, 'message' => 'Data satgas berhasil diperbarui.']);
  }

  public function destroy($id)
  {
    $satgas = User::where('id', $id)->where('role', 'satgas')->firstOrFail();
    $satgas->delete();

    return response()->json(['success' => true, 'message' => 'Satgas berhasil dihapus.']);
  }
}
