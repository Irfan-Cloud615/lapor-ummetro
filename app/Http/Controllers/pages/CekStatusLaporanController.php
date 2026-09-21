<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use App\Models\Pengaduan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CekStatusLaporanController extends Controller
{
    /** Urutan tahapan penanganan pengaduan (selain status Ditolak) */
    private const TAHAPAN = ['Baru', 'Diproses', 'Investigasi', 'Selesai'];

    /** Informasi tampilan tiap status pengaduan */
    private const STATUS_META = [
        'Baru' => [
            'label' => 'Menunggu Verifikasi',
            'deskripsi' => 'Laporanmu sudah kami terima dan sedang menunggu verifikasi tim penanganan.',
            'ikon' => 'ti-inbox',
            'warna' => 'info',
        ],
        'Diproses' => [
            'label' => 'Sedang Diproses',
            'deskripsi' => 'Laporanmu sedang ditindaklanjuti oleh tim penanganan perundungan.',
            'ikon' => 'ti-progress',
            'warna' => 'primary',
        ],
        'Investigasi' => [
            'label' => 'Dalam Investigasi',
            'deskripsi' => 'Tim sedang melakukan pendalaman dan pemeriksaan terkait laporanmu.',
            'ikon' => 'ti-zoom-in',
            'warna' => 'warning',
        ],
        'Selesai' => [
            'label' => 'Selesai Ditangani',
            'deskripsi' => 'Penanganan laporan telah selesai. Terima kasih telah berani melapor.',
            'ikon' => 'ti-circle-check',
            'warna' => 'success',
        ],
        'Ditolak' => [
            'label' => 'Tidak Ditindaklanjuti',
            'deskripsi' => 'Laporan tidak dapat ditindaklanjuti. Hubungi tim penanganan apabila membutuhkan penjelasan.',
            'ikon' => 'ti-circle-x',
            'warna' => 'danger',
        ],
    ];

    public function index()
    {
        return view('content.pages.cek-status-laporan');
    }

    /**
     * Verifikasi PIN akses, lalu tampilkan status penanganan laporannya.
     */
    public function check(Request $request)
    {
        $validated = $request->validate([
            'pin_akses' => ['required', 'digits:6'],
        ], [
            'pin_akses.required' => 'PIN akses wajib diisi.',
            'pin_akses.digits' => 'PIN akses terdiri dari 6 angka.',
        ]);

        $pengaduan = $this->cariBerdasarkanPin($validated['pin_akses']);

        if (!$pengaduan) {
            return back()->withErrors([
                'pin_akses' => 'PIN akses tidak ditemukan. Periksa kembali PIN yang kamu simpan saat melapor.',
            ]);
        }

        return view('content.pages.cek-status-laporan', [
            'pengaduan' => $pengaduan,
            'statusMeta' => self::STATUS_META[$pengaduan->status_pengaduan] ?? self::STATUS_META['Baru'],
            'tahapan' => $this->susunTahapan($pengaduan),
        ]);
    }

    /**
     * Cari pengaduan yang cocok dengan PIN akses.
     *
     * PIN disimpan sebagai hash bcrypt dengan salt berbeda tiap baris,
     * sehingga pencocokan dilakukan satu per satu dari laporan terbaru.
     */
    private function cariBerdasarkanPin(string $pin): ?Pengaduan
    {
        $kandidat = Pengaduan::with('kategori')
            ->withCount('lampiran_bukti')
            ->whereNotNull('pin_akses')
            ->orderByDesc('id')
            ->get();

        foreach ($kandidat as $pengaduan) {
            if (Hash::check($pin, $pengaduan->pin_akses)) {
                return $pengaduan;
            }
        }

        return null;
    }

    /**
     * Susun linimasa tahapan penanganan sesuai status pengaduan saat ini.
     */
    private function susunTahapan(Pengaduan $pengaduan): array
    {
        $status = $pengaduan->status_pengaduan;
        $waktuDiterima = optional($pengaduan->created_at)->format('d/m/Y H:i');
        $waktuDiperbarui = optional($pengaduan->updated_at)->format('d/m/Y H:i');

        $judul = [
            'Baru' => 'Laporan Diterima',
            'Diproses' => 'Sedang Diproses',
            'Investigasi' => 'Dalam Investigasi',
            'Selesai' => 'Selesai Ditangani',
        ];

        // Status "Ditolak" tidak mengikuti alur normal
        if ($status === 'Ditolak') {
            return [
                $this->barisTahap('Baru', $judul['Baru'], 'Laporan berhasil masuk ke sistem.', $waktuDiterima, 'selesai'),
                $this->barisTahap(
                    'Ditolak',
                    'Tidak Ditindaklanjuti',
                    'Laporan tidak dapat ditindaklanjuti oleh tim penanganan.',
                    $waktuDiperbarui,
                    'ditolak'
                ),
            ];
        }

        $posisi = array_search($status, self::TAHAPAN, true);
        $posisi = $posisi === false ? 0 : $posisi;

        $tahapan = [];
        foreach (self::TAHAPAN as $index => $nama) {
            $keadaan = 'menunggu';
            if ($index < $posisi) {
                $keadaan = 'selesai';
            } elseif ($index === $posisi) {
                $keadaan = 'aktif';
            }

            $waktu = null;
            if ($nama === 'Baru') {
                $waktu = $waktuDiterima;
            } elseif ($nama === 'Selesai' && $keadaan === 'aktif') {
                $waktu = $waktuDiperbarui;
            }

            $tahapan[] = $this->barisTahap($nama, $judul[$nama], $this->deskripsiTahap($nama, $keadaan), $waktu, $keadaan);
        }

        return $tahapan;
    }

    /**
     * Deskripsi tiap tahapan; memakai deskripsi status saat tahapan sedang aktif.
     */
    private function deskripsiTahap(string $nama, string $keadaan): string
    {
        $deskripsi = [
            'Baru' => 'Laporan berhasil masuk ke sistem.',
            'Diproses' => 'Laporan ditindaklanjuti oleh tim penanganan.',
            'Investigasi' => 'Tim melakukan pendalaman dan pemeriksaan kasus.',
            'Selesai' => 'Penanganan laporan tuntas.',
        ];

        if ($keadaan === 'aktif' && isset(self::STATUS_META[$nama])) {
            return self::STATUS_META[$nama]['deskripsi'];
        }

        return $deskripsi[$nama];
    }

    /**
     * Satu baris linimasa: label, deskripsi, waktu, dan gaya tampilannya.
     */
    private function barisTahap(string $nama, string $label, string $deskripsi, ?string $waktu, string $keadaan): array
    {
        switch ($keadaan) {
            case 'selesai':
                $ikon = 'ti-check';
                $lingkaran = 'bg-label-success';
                $judul = '';
                break;
            case 'aktif':
                $ikon = self::STATUS_META[$nama]['ikon'];
                $lingkaran = 'bg-label-' . self::STATUS_META[$nama]['warna'];
                $judul = 'text-' . self::STATUS_META[$nama]['warna'];
                break;
            case 'ditolak':
                $ikon = 'ti-x';
                $lingkaran = 'bg-label-danger';
                $judul = 'text-danger';
                break;
            default: // menunggu
                $ikon = 'ti-clock';
                $lingkaran = 'bg-label-secondary';
                $judul = 'text-muted';
                break;
        }

        return [
            'label' => $label,
            'deskripsi' => $deskripsi,
            'waktu' => $waktu,
            'ikon' => $ikon,
            'lingkaran' => $lingkaran,
            'judul' => $judul,
        ];
    }
}
