<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use App\Models\KategoriKasus;
use App\Models\Korban;
use App\Models\LampiranBukti;
use App\Models\Pelapor;
use App\Models\Pengaduan;
use App\Models\Terlapor;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LaporPerundunganController extends Controller
{
  /** Pilihan status pelapor untuk select2 */
  public const STATUS_PELAPOR = ['Korban', 'Saksi', 'Lainnya'];

  /** Pilihan status terlapor untuk select2 */
  public const STATUS_TERLAPOR = ['Mahasiswa', 'Dosen', 'Tenaga Kependidikan', 'Umum', 'Tidak Diketahui'];

  /** Jumlah maksimal file bukti per pengaduan */
  private const MAX_LAMPIRAN = 5;

  /** Maksimal percobaan pembuatan kode pengaduan unik */
  private const MAX_ATTEMPT_KODE = 10;

  public function index()
  {
    return view('content.pages.lapor-perundungan', [
      'kategoris' => KategoriKasus::where('is_active', true)->orderBy('nama_kategori')->get(),
      'statusPelaporOptions' => self::STATUS_PELAPOR,
      'statusTerlaporOptions' => self::STATUS_TERLAPOR,
    ]);
  }

  public function store(Request $request)
  {
    $validated = $request->validate($this->validationRules(), $this->validationMessages());

    // PIN akses dibuat oleh sistem, disimpan sebagai hash,
    // dan hanya ditampilkan sekali (pada respons sukses) kepada pelapor.
    $pin = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

    $pengaduan = DB::transaction(function () use ($request, $validated, $pin) {
      $pelapor = Pelapor::create([
        'user_id' => auth()->id(),
        'nama_lengkap' => $validated['nama_pelapor'] ?? 'Anonim',
        'npm_nip' => $validated['npm_nip'] ?? null,
        'status_pelapor' => $validated['status_pelapor'] ?? 'Lainnya',
        'kontak_wa' => $validated['kontak_wa'],
      ]);

      $statusPelapor = $validated['status_pelapor'] ?? 'Lainnya';
      $adalahKorban = $statusPelapor === 'Korban';

      $korban = Korban::create([
        'nama_lengkap' => $adalahKorban ? ($validated['nama_pelapor'] ?? 'Anonim') : ($validated['nama_korban'] ?? 'Anonim'),
        'program_studi' => $adalahKorban ? null : ($validated['program_studi'] ?? null),
        'usia' => $adalahKorban ? null : ($validated['usia'] ?? null),
      ]);

      $terlapor = Terlapor::create([
        'nama_lengkap' => $validated['nama_terlapor'] ?? 'Tidak Diketahui',
        'status_terlapor' => $validated['status_terlapor'] ?? 'Tidak Diketahui',
      ]);

      $pengaduan = $this->createPengaduan($pelapor->id, $korban->id, $terlapor->id, $validated, $pin);

      foreach ($request->file('bukti', []) as $file) {
        LampiranBukti::create([
          'pengaduan_id' => $pengaduan->id,
          'nama_file' => $file->store('bukti-pengaduan', 'public'),
          'tipe_file' => $file->extension(),
        ]);
      }

      return $pengaduan;
    });

    return response()->json([
      'message' => 'Pengaduan berhasil dikirim.',
      'kode_pengaduan' => $pengaduan->kode_pengaduan,
      'pin_akses' => $pin,
    ], 201);
  }

  /**
   * Aturan validasi formulir pengaduan.
   */
  private function validationRules(): array
  {
    return [
      // Data pelapor
      'nama_pelapor' => ['nullable', 'string', 'max:150'],
      'npm_nip' => ['nullable', 'string', 'max:50'],
      'status_pelapor' => ['nullable', 'string'],
      'kontak_wa' => ['required', 'string', 'max:20'],

      // Data korban
      'nama_korban' => ['nullable', 'string', 'max:70'],
      'program_studi' => ['nullable', 'string', 'max:60'],
      'usia' => ['nullable', 'integer', 'min:5', 'max:100'],

      // Data terlapor
      'nama_terlapor' => ['nullable', 'string', 'max:70'],
      'status_terlapor' => ['nullable', 'string'],

      // Detail kejadian
      'kategori_id' => ['required', 'integer', 'exists:kategori_kasus,id'],
      'tanggal_kejadian' => ['required', 'date', 'before_or_equal:today'],
      'waktu_kejadian' => ['required', 'date_format:H:i'],
      'lokasi_kejadian' => ['required', 'string', 'max:255'],
      'kronologi' => ['required', 'string', 'min:20'],
      'saksi' => ['nullable', 'string'],

      // Bukti pendukung
      'bukti' => ['nullable', 'array', 'max:' . self::MAX_LAMPIRAN],
      'bukti.*' => ['nullable', 'file', 'max:5120', 'mimes:jpg,jpeg,png,webp,pdf,doc,docx,mp4'],
    ];
  }

  /**
   * Pesan validasi dalam Bahasa Indonesia.
   */
  private function validationMessages(): array
  {
    return [
      'nama_pelapor.required' => 'Nama pelapor wajib diisi.',
      'nama_korban.required_unless' => 'Nama korban wajib diisi karena pelapor bukan korban itu sendiri.',
      'nama_terlapor.required' => 'Nama terlapor wajib diisi.',
      'status_pelapor.required' => 'Status pelapor wajib dipilih.',
      'status_pelapor.in' => 'Status pelapor tidak valid.',
      'kontak_wa.required' => 'Nomor WhatsApp wajib diisi.',
      'kategori_id.required' => 'Kategori kejadian wajib dipilih.',
      'kategori_id.exists' => 'Kategori kejadian tidak valid.',
      'tanggal_kejadian.required' => 'Tanggal kejadian wajib diisi.',
      'tanggal_kejadian.before_or_equal' => 'Tanggal kejadian tidak boleh melebihi hari ini.',
      'waktu_kejadian.required' => 'Waktu kejadian wajib diisi.',
      'waktu_kejadian.date_format' => 'Format waktu kejadian tidak valid.',
      'lokasi_kejadian.required' => 'Lokasi kejadian wajib diisi.',
      'kronologi.required' => 'Kronologi kejadian wajib diisi.',
      'kronologi.min' => 'Kronologi minimal :min karakter agar dapat ditindaklanjuti.',
      'bukti.max' => 'Maksimal :max file bukti.',
      'bukti.*.file' => 'Bukti harus berupa berkas.',
      'bukti.*.max' => 'Ukuran tiap file bukti maksimal 5 MB.',
      'bukti.*.mimes' => 'Format bukti harus salah satu dari: jpg, jpeg, png, webp, pdf, doc, docx, mp4.',
    ];
  }

  /**
   * Buat pengaduan dengan kode unik.
   *
   * Kode dibuat sistem dan dijamin unik walaupun banyak pelapor
   * mengirim pengaduan secara bersamaan: kombinasi random + retry
   * saat terjadi pelanggaran unique index di database.
   */
  private function createPengaduan(int $pelaporId, int $korbanId, int $terlaporId, array $validated, string $pin): Pengaduan
  {
    $data = [
      'pelapor_id' => $pelaporId,
      'korban_id' => $korbanId,
      'terlapor_id' => $terlaporId,
      'kategori_id' => $validated['kategori_id'],
      'tanggal_kejadian' => $validated['tanggal_kejadian'],
      'waktu_kejadian' => $validated['waktu_kejadian'],
      'lokasi_kejadian' => $validated['lokasi_kejadian'],
      'kronologi' => $validated['kronologi'],
      'saksi' => $validated['saksi'],
      'pin_akses' => Hash::make($pin),
      'status_pengaduan' => 'Baru',
      // satgas_id sengaja tidak diisi (null), akan ditugaskan oleh tim satgas
    ];

    $attempt = 0;
    do {
      $attempt++;
      try {
        return Pengaduan::create($data + ['kode_pengaduan' => $this->generateKodePengaduan()]);
      } catch (QueryException $e) {
        // 23000 = integrity constraint violation (kode duplikat saat submit bersamaan)
        $isKodeDuplikat = str_contains($e->getMessage(), 'kode_pengaduan')
          && in_array((string) ($e->errorInfo[0] ?? $e->getCode()), ['23000', '1062', '19'], true);

        if (!$isKodeDuplikat || $attempt >= self::MAX_ATTEMPT_KODE) {
          throw $e;
        }
      }
    } while (true);
  }

  /**
   * Generate kode pengaduan, contoh: LPR-20260919-8XK2
   */
  private function generateKodePengaduan(): string
  {
    do {
      $kode = 'LPR-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4));
    } while (Pengaduan::where('kode_pengaduan', $kode)->exists());

    return $kode;
  }
}
