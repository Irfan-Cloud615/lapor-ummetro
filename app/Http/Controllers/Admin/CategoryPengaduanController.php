<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriKasus;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryPengaduanController extends Controller
{
  public function index()
  {
    return view('content.admin.category-pengaduan');
  }

  public function datatable(Request $request)
  {
    $query = KategoriKasus::query();

    if ($searchValue = $request->input('search.value')) {
      $query->where('nama_kategori', 'like', "%{$searchValue}%");
    }

    $totalData = KategoriKasus::count();
    $totalFiltered = $query->count();

    $start = (int) $request->input('start', 0);
    $length = (int) $request->input('length', 10);

    $orderColumnIndex = $request->input('order.0.column', 1);
    $orderDir = $request->input('order.0.dir', 'asc');

    $columnsMap = [
      0 => 'id',
      1 => 'nama_kategori',
      2 => 'is_active',
      3 => 'created_at',
    ];

    $orderColumn = $columnsMap[$orderColumnIndex] ?? 'nama_kategori';
    $query->orderBy($orderColumn, $orderDir);

    if ($length !== -1) {
      $query->skip($start)->take($length);
    }

    $categories = $query->get();

    $data = [];
    foreach ($categories as $index => $category) {
      $data[] = [
        'no' => $start + $index + 1,
        'id' => $category->id,
        'nama_kategori' => $category->nama_kategori,
        'is_active' => $category->is_active ? 1 : 0,
        'created_at' => $category->created_at ? $category->created_at->format('d/m/Y H:i') : '-',
      ];
    }

    return response()->json([
      'draw' => (int) $request->input('draw'),
      'recordsTotal' => $totalData,
      'recordsFiltered' => $totalFiltered,
      'data' => $data,
    ]);
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'nama_kategori' => 'required|string|max:100|unique:kategori_kasus,nama_kategori',
      'is_active' => 'required|in:0,1,true,false',
    ], [
      'nama_kategori.required' => 'Nama kategori wajib diisi.',
      'nama_kategori.max' => 'Nama kategori maksimal 100 karakter.',
      'nama_kategori.unique' => 'Nama kategori sudah ada.',
      'is_active.required' => 'Status wajib dipilih.',
    ]);

    KategoriKasus::create([
      'nama_kategori' => $validated['nama_kategori'],
      'is_active' => filter_var($validated['is_active'], FILTER_VALIDATE_BOOLEAN),
    ]);

    return response()->json([
      'success' => true,
      'message' => 'Kategori kasus berhasil ditambahkan.',
    ]);
  }

  public function update(Request $request, $id)
  {
    $category = KategoriKasus::findOrFail($id);

    $validated = $request->validate([
      'nama_kategori' => [
        'required',
        'string',
        'max:100',
        Rule::unique('kategori_kasus', 'nama_kategori')->ignore($category->id),
      ],
      'is_active' => 'required|in:0,1,true,false',
    ], [
      'nama_kategori.required' => 'Nama kategori wajib diisi.',
      'nama_kategori.max' => 'Nama kategori maksimal 100 karakter.',
      'nama_kategori.unique' => 'Nama kategori sudah ada.',
      'is_active.required' => 'Status wajib dipilih.',
    ]);

    $category->update([
      'nama_kategori' => $validated['nama_kategori'],
      'is_active' => filter_var($validated['is_active'], FILTER_VALIDATE_BOOLEAN),
    ]);

    return response()->json([
      'success' => true,
      'message' => 'Kategori kasus berhasil diperbarui.',
    ]);
  }

  public function destroy($id)
  {
    $category = KategoriKasus::findOrFail($id);
    $category->delete();

    return response()->json([
      'success' => true,
      'message' => 'Kategori kasus berhasil dihapus.',
    ]);
  }
}
