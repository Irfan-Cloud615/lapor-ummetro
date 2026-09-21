<?php

namespace App\Http\Controllers\Satgas;

use App\Http\Controllers\Controller;
use App\Models\LogPenanganan;
use App\Models\Pengaduan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MyCasesController extends Controller
{
  /**
   * Display a listing of assigned cases for the active Satgas user.
   */
  public function index(Request $request)
  {
    $satgasId = Auth::id();

    $query = Pengaduan::with(['kategori', 'pelapor', 'korban', 'terlapor', 'lampiran_bukti', 'logPenanganan.user'])
      ->where('satgas_id', $satgasId);

    // Filter by status if provided
    if ($request->filled('status')) {
      $query->where('status_pengaduan', $request->status);
    }

    // Search query
    if ($request->filled('search')) {
      $search = $request->search;
      $query->where(function ($q) use ($search) {
        $q->where('kode_pengaduan', 'like', "%{$search}%")
          ->orWhere('kronologi', 'like', "%{$search}%")
          ->orWhereHas('kategori', function ($k) use ($search) {
            $k->where('nama_kategori', 'like', "%{$search}%");
          });
      });
    }

    $cases = $query->latest()->get();

    // Selected case (default to first case or specified case_id parameter)
    $selectedId = $request->get('case_id', $cases->first()?->id);
    $activeCase = $cases->firstWhere('id', $selectedId) ?? $cases->first();

    return view('content.satgas.my-cases', compact('cases', 'activeCase'));
  }

  /**
   * Display details of a specific case via AJAX.
   */
  public function show($id)
  {
    $satgasId = Auth::id();

    $case = Pengaduan::with(['kategori', 'pelapor', 'korban', 'terlapor', 'lampiran_bukti', 'logPenanganan.user'])
      ->where('satgas_id', $satgasId)
      ->findOrFail($id);

    return response()->json([
      'status' => 'success',
      'data' => $case
    ]);
  }

  /**
   * Store a new progress log note and update the status of the case.
   */
  public function storeLog(Request $request, $id)
  {
    $satgasId = Auth::id();

    $case = Pengaduan::where('satgas_id', $satgasId)->findOrFail($id);

    $request->validate([
      'catatan' => 'required|string',
      'status_pengaduan' => 'required|string|in:Diproses,Investigasi,Selesai',
      'is_public' => 'nullable|boolean',
    ]);

    DB::transaction(function () use ($request, $case) {
      // Save log entry
      LogPenanganan::create([
        'pengaduan_id' => $case->id,
        'user_id' => Auth::id(),
        'catatan' => $request->catatan,
        'is_public' => $request->has('is_public') ? (bool) $request->is_public : false,
      ]);

      // Update case status if changed
      if ($case->status_pengaduan !== $request->status_pengaduan) {
        $case->update([
          'status_pengaduan' => $request->status_pengaduan
        ]);
      }
    });

    if ($request->wantsJson() || $request->ajax()) {
      $updatedCase = Pengaduan::with(['kategori', 'pelapor', 'korban', 'terlapor', 'lampiran_bukti', 'logPenanganan.user'])
        ->find($id);

      return response()->json([
        'status' => 'success',
        'message' => 'Catatan penanganan berhasil disimpan!',
        'data' => $updatedCase
      ]);
    }

    return redirect()->route('satgas.my-cases.index', ['case_id' => $id])
      ->with('success', 'Catatan penanganan dan status berhasil diperbarui!');
  }
}
