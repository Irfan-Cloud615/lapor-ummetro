<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
  public function index()
  {
    return view('content.admin.faq');
  }

  public function datatable(Request $request)
  {
    $query = Faq::query();

    if ($searchValue = $request->input('search.value')) {
      $query->where(function ($q) use ($searchValue) {
        $q->where('pertanyaan', 'like', "%{$searchValue}%")
          ->orWhere('jawaban', 'like', "%{$searchValue}%");
      });
    }

    $totalData = Faq::count();
    $totalFiltered = $query->count();

    $start = (int) $request->input('start', 0);
    $length = (int) $request->input('length', 10);

    $orderColumnIndex = $request->input('order.0.column', 3);
    $orderDir = $request->input('order.0.dir', 'asc');

    $columnsMap = [
      0 => 'id',
      1 => 'pertanyaan',
      2 => 'jawaban',
      3 => 'urutan',
      4 => 'is_active',
      5 => 'created_at',
    ];

    $orderColumn = $columnsMap[$orderColumnIndex] ?? 'urutan';
    $query->orderBy($orderColumn, $orderDir)->orderBy('id', 'asc');

    if ($length !== -1) {
      $query->skip($start)->take($length);
    }

    $faqs = $query->get();

    $data = [];
    foreach ($faqs as $index => $faq) {
      $data[] = [
        'no' => $start + $index + 1,
        'id' => $faq->id,
        'pertanyaan' => $faq->pertanyaan,
        'jawaban' => $faq->jawaban,
        'urutan' => $faq->urutan,
        'is_active' => $faq->is_active ? 1 : 0,
        'created_at' => $faq->created_at ? $faq->created_at->format('d/m/Y H:i') : '-',
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
      'pertanyaan' => 'required|string|max:255',
      'jawaban' => 'required|string',
      'urutan' => 'nullable|integer|min:0',
      'is_active' => 'required|in:0,1,true,false',
    ], [
      'pertanyaan.required' => 'Pertanyaan wajib diisi.',
      'pertanyaan.max' => 'Pertanyaan maksimal 255 karakter.',
      'jawaban.required' => 'Jawaban wajib diisi.',
      'is_active.required' => 'Status wajib dipilih.',
    ]);

    Faq::create([
      'pertanyaan' => $validated['pertanyaan'],
      'jawaban' => $validated['jawaban'],
      'urutan' => $validated['urutan'] ?? 0,
      'is_active' => filter_var($validated['is_active'], FILTER_VALIDATE_BOOLEAN),
    ]);

    return response()->json([
      'success' => true,
      'message' => 'Pertanyaan FAQ berhasil ditambahkan.',
    ]);
  }

  public function update(Request $request, $id)
  {
    $faq = Faq::findOrFail($id);

    $validated = $request->validate([
      'pertanyaan' => 'required|string|max:255',
      'jawaban' => 'required|string',
      'urutan' => 'nullable|integer|min:0',
      'is_active' => 'required|in:0,1,true,false',
    ], [
      'pertanyaan.required' => 'Pertanyaan wajib diisi.',
      'pertanyaan.max' => 'Pertanyaan maksimal 255 karakter.',
      'jawaban.required' => 'Jawaban wajib diisi.',
      'is_active.required' => 'Status wajib dipilih.',
    ]);

    $faq->update([
      'pertanyaan' => $validated['pertanyaan'],
      'jawaban' => $validated['jawaban'],
      'urutan' => $validated['urutan'] ?? 0,
      'is_active' => filter_var($validated['is_active'], FILTER_VALIDATE_BOOLEAN),
    ]);

    return response()->json([
      'success' => true,
      'message' => 'Pertanyaan FAQ berhasil diperbarui.',
    ]);
  }

  public function destroy($id)
  {
    $faq = Faq::findOrFail($id);
    $faq->delete();

    return response()->json([
      'success' => true,
      'message' => 'Pertanyaan FAQ berhasil dihapus.',
    ]);
  }
}
