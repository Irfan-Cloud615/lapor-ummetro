<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengaduan;
use Illuminate\Http\Request;

class RiwayatPengaduanController extends Controller
{
  public function index()
  {
    return view('content.admin.riwayat-pengaduan');
  }

  public function datatable(Request $request)
  {
    // Mengambil data pengaduan yang status pengaduannya BUKAN 'Baru'
    $query = Pengaduan::with(['pelapor', 'korban', 'terlapor', 'kategori', 'satgas'])
      ->where('status_pengaduan', '!=', 'Baru');

    if ($searchValue = $request->input('search.value')) {
      $query->where(function ($q) use ($searchValue) {
        $q->where('kode_pengaduan', 'like', "%{$searchValue}%")
          ->orWhereHas('pelapor', function ($qp) use ($searchValue) {
            $qp->where('nama_lengkap', 'like', "%{$searchValue}%");
          })
          ->orWhereHas('kategori', function ($qk) use ($searchValue) {
            $qk->where('nama_kategori', 'like', "%{$searchValue}%");
          })
          ->orWhere('status_pengaduan', 'like', "%{$searchValue}%");
      });
    }

    $totalData = Pengaduan::where('status_pengaduan', '!=', 'Baru')->count();
    $totalFiltered = $query->count();

    $start = (int) $request->input('start', 0);
    $length = (int) $request->input('length', 10);

    $orderColumnIndex = $request->input('order.0.column', 1);
    $orderDir = $request->input('order.0.dir', 'desc');

    $columnsMap = [
      0 => 'id',
      1 => 'kode_pengaduan',
      2 => 'pelapor_id',
      3 => 'kategori_id',
      4 => 'tanggal_kejadian',
      5 => 'status_pengaduan',
      6 => 'created_at',
    ];

    $orderColumn = $columnsMap[$orderColumnIndex] ?? 'created_at';
    $query->orderBy($orderColumn, $orderDir);

    if ($length !== -1) {
      $query->skip($start)->take($length);
    }

    $pengaduans = $query->get();

    $data = [];
    foreach ($pengaduans as $index => $item) {
      $data[] = [
        'no' => $start + $index + 1,
        'id' => $item->id,
        'kode_pengaduan' => $item->kode_pengaduan,
        'pelapor' => $item->pelapor->nama_lengkap ?? '-',
        'kategori' => $item->kategori->nama_kategori ?? '-',
        'tanggal_kejadian' => $item->tanggal_kejadian ? $item->tanggal_kejadian->format('d/m/Y') : '-',
        'status_pengaduan' => $item->status_pengaduan,
        'satgas' => $item->satgas->name ?? '-',
        'created_at' => $item->created_at ? $item->created_at->format('d/m/Y H:i') : '-',
      ];
    }

    return response()->json([
      'draw' => (int) $request->input('draw'),
      'recordsTotal' => $totalData,
      'recordsFiltered' => $totalFiltered,
      'data' => $data,
    ]);
  }
}
