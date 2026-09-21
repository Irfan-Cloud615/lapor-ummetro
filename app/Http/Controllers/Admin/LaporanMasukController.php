<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengaduan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LaporanMasukController extends Controller
{
  public function index()
  {
    // Pengaduan yang belum ditugaskan ke Satgas (satgas_id is null)
    $unassignedPengaduans = Pengaduan::with(['pelapor', 'korban', 'terlapor', 'kategori'])
      ->whereNull('satgas_id')
      ->orderBy('created_at', 'desc')
      ->get();

    // Pengaduan yang telah ditugaskan ke Satgas, dikelompokkan per satgas_id
    $assignedPengaduans = Pengaduan::with(['pelapor', 'korban', 'terlapor', 'kategori', 'satgas'])
      ->whereNotNull('satgas_id')
      ->whereNotIn('status_pengaduan', ['Baru', 'Ditutup'])
      ->orderBy('updated_at', 'desc')
      ->get()
      ->groupBy('satgas_id');

    // Pengguna bertindak sebagai Satgas (role: satgas)
    $satgasUsers = User::where('role', 'satgas')->orderBy('name', 'asc')->get();

    return view('content.admin.laporan-masuk', compact('unassignedPengaduans', 'assignedPengaduans', 'satgasUsers'));
  }

  public function show($id)
  {
    $pengaduan = Pengaduan::with([
      'pelapor',
      'korban',
      'terlapor',
      'kategori',
      'satgas',
      'lampiran_bukti',
    ])->findOrFail($id);

    return response()->json([
      'success' => true,
      'pengaduan' => [
        'id' => $pengaduan->id,
        'kode_pengaduan' => $pengaduan->kode_pengaduan,
        'status_pengaduan' => $pengaduan->status_pengaduan,
        'kategori' => $pengaduan->kategori->nama_kategori ?? '-',
        'pelapor' => [
          'nama_lengkap' => $pengaduan->pelapor->nama_lengkap ?? '-',
          'npm_nip' => $pengaduan->pelapor->npm_nip ?? '-',
          'status_pelapor' => $pengaduan->pelapor->status_pelapor ?? '-',
          'kontak_wa' => $pengaduan->pelapor->kontak_wa ?? '-',
        ],
        'korban' => [
          'nama_lengkap' => $pengaduan->korban->nama_lengkap ?? '-',
          'program_studi' => $pengaduan->korban->program_studi ?? '-',
          'usia' => $pengaduan->korban->usia ?? '-',
        ],
        'terlapor' => [
          'nama_lengkap' => $pengaduan->terlapor->nama_lengkap ?? '-',
          'status_terlapor' => $pengaduan->terlapor->status_terlapor ?? '-',
        ],
        'tanggal_kejadian' => $pengaduan->tanggal_kejadian ? $pengaduan->tanggal_kejadian->format('d/m/Y') : '-',
        'waktu_kejadian' => $pengaduan->waktu_kejadian ?? '-',
        'lokasi_kejadian' => $pengaduan->lokasi_kejadian ?? '-',
        'kronologi' => $pengaduan->kronologi ?? '-',
        'saksi' => $pengaduan->saksi ?? '-',
        'created_at' => $pengaduan->created_at ? $pengaduan->created_at->format('d M Y H:i') : '-',
        'updated_at' => $pengaduan->updated_at ? $pengaduan->updated_at->toIso8601String() : null,
        'satgas' => $pengaduan->satgas ? [
          'id' => $pengaduan->satgas->id,
          'name' => $pengaduan->satgas->name,
        ] : null,
        'lampiran_bukti' => $pengaduan->lampiran_bukti->map(function ($bukti) {
          $filePath = asset('storage/' . $bukti->nama_file);
          $ext = strtolower(pathinfo($bukti->nama_file, PATHINFO_EXTENSION));
          $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
          return [
            'id' => $bukti->id,
            'file_path' => $filePath,
            'file_name' => basename($bukti->nama_file),
            'tipe_file' => $bukti->tipe_file,
            'is_image' => $isImage,
          ];
        }),
      ],
    ]);
  }

  public function assign(Request $request, $id)
  {
    $request->validate([
      'satgas_id' => 'required|exists:users,id',
    ]);

    $pengaduan = Pengaduan::findOrFail($id);
    $satgas = User::where('role', 'satgas')->findOrFail($request->satgas_id);

    $updateData = [
      'satgas_id' => $satgas->id,
    ];

    if ($pengaduan->status_pengaduan === 'Baru') {
      $updateData['status_pengaduan'] = 'Diproses';
    }

    $pengaduan->update($updateData);

    return response()->json([
      'success' => true,
      'message' => "Pengaduan berhasil ditugaskan kepada Satgas {$satgas->name}.",
      'satgas_id' => $satgas->id,
      'satgas_name' => $satgas->name,
      'status_pengaduan' => $pengaduan->status_pengaduan,
    ]);
  }

  public function updateStatus(Request $request, $id)
  {
    $request->validate([
      'status_pengaduan' => 'required|in:Baru,Diproses,Investigasi,Selesai,Ditolak,Ditutup'
    ]);

    $pengaduan = Pengaduan::findOrFail($id);
    $pengaduan->update([
      'status_pengaduan' => $request->status_pengaduan,
    ]);

    return response()->json([
      'success' => true,
      'message' => "Status pengaduan berhasil diperbarui menjadi '{$request->status_pengaduan}'.",
      'status_pengaduan' => $pengaduan->status_pengaduan,
    ]);
  }

  public function destroy($id)
  {
    $pengaduan = Pengaduan::findOrFail($id);

    // Hapus file fisik lampiran bukti
    foreach ($pengaduan->lampiran_bukti as $bukti) {
      if (Storage::disk('public')->exists($bukti->nama_file)) {
        Storage::disk('public')->delete($bukti->nama_file);
      }
      $bukti->delete();
    }

    $korban = $pengaduan->korban;
    $terlapor = $pengaduan->terlapor;
    $pelapor = $pengaduan->pelapor;

    $pengaduan->delete();

    if ($korban && $korban->pengaduan()->count() === 0) {
      $korban->delete();
    }
    if ($terlapor && $terlapor->pengaduan()->count() === 0) {
      $terlapor->delete();
    }
    if ($pelapor && $pelapor->pengaduan()->count() === 0) {
      $pelapor->delete();
    }

    return response()->json([
      'success' => true,
      'message' => 'Pengaduan berhasil dihapus.',
    ]);
  }
}
