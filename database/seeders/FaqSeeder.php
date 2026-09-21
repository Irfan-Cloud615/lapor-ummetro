<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    /**
     * Pertanyaan umum awal untuk halaman FAQ layanan pengaduan perundungan.
     */
    public function run(): void
    {
        $faqs = [
            [
                'pertanyaan' => 'Apa itu layanan Lapor Perundungan?',
                'jawaban' => 'Lapor Perundungan adalah kanal pengaduan resmi untuk melaporkan kejadian perundungan di lingkungan Universitas Muhammadiyah Metro. Melalui layanan ini, korban maupun saksi dapat menyampaikan laporan dengan aman, melampirkan bukti, lalu memantau proses penanganannya hingga selesai — semuanya tanpa perlu datang ke ruang layanan.',
                'urutan' => 1,
            ],
            [
                'pertanyaan' => 'Siapa saja yang dapat menggunakan layanan ini?',
                'jawaban' => 'Siapa pun yang mengalami, menyaksikan, atau mengetahui kejadian perundungan di lingkungan kampus. Saat melapor, kamu akan memilih status pelapor: Korban, Saksi, atau Lainnya. Jika kamu adalah korban itu sendiri, data korban otomatis mengikuti data pelapor sehingga kamu tidak perlu mengisinya dua kali.',
                'urutan' => 2,
            ],
            [
                'pertanyaan' => 'Apakah identitas saya dirahasiakan dari pihak yang saya laporkan?',
                'jawaban' => 'Ya. Nama dan kontak yang kamu isikan hanya digunakan oleh tim penanganan berwenang untuk verifikasi dan pendampingan, dan tim tersebut terikat kewajiban menjaga kerahasiaan. Identitasmu tidak akan pernah dibagikan kepada pihak yang kamu laporkan maupun pihak lain yang tidak berkepentingan dengan kasus.',
                'urutan' => 3,
            ],
            [
                'pertanyaan' => 'Data apa saja yang perlu saya siapkan sebelum melapor?',
                'jawaban' => 'Siapkan nama lengkap dan nomor WhatsApp aktifmu, identitas pihak yang melakukan perundungan beserta statusnya (mahasiswa, dosen, tenaga kependidikan, atau umum), kategori kejadian, waktu dan lokasi kejadian, serta kronologi (minimal 20 karakter). NPM/NIP boleh dikosongkan. Semakin lengkap kronologi yang kamu tulis, semakin cepat laporan dapat diverifikasi.',
                'urutan' => 4,
            ],
            [
                'pertanyaan' => 'Bukti apa saja yang bisa saya lampirkan?',
                'jawaban' => 'Kamu dapat melampirkan hingga 5 file dengan ukuran maksimal 5 MB per file, berformat JPG, JPEG, PNG, WebP, PDF, DOC, DOCX, atau MP4 — misalnya tangkapan layar percakapan, foto, dokumen, atau rekaman video. Lampiran bersifat opsional, tetapi sangat membantu memperkuat verifikasi laporan.',
                'urutan' => 5,
            ],
            [
                'pertanyaan' => 'Apa itu PIN akses dan bagaimana cara menggunakannya?',
                'jawaban' => 'PIN akses adalah 6 angka yang dibuat otomatis oleh sistem ketika laporanmu berhasil terkirim, dan hanya ditampilkan satu kali di layar konfirmasi — pastikan kamu menyimpannya. PIN inilah yang kamu gunakan untuk membuka status penanganan laporanmu di halaman Cek Status Laporan. Kamu juga akan menerima kode pengaduan (contoh: LPR-20260919-8XK2) sebagai identitas laporanmu.',
                'urutan' => 6,
            ],
            [
                'pertanyaan' => 'Bagaimana jika saya lupa atau kehilangan PIN akses?',
                'jawaban' => 'PIN tidak dapat dilihat kembali karena sengaja hanya ditampilkan sekali demi keamanan. Jika hilang, hubungi tim penanganan perundungan kampus secara langsung; identitasmu akan diverifikasi secara manual dan laporanmu dicari berdasarkan kode pengaduan serta data pelapor.',
                'urutan' => 7,
            ],
            [
                'pertanyaan' => 'Apa yang terjadi setelah laporan saya dikirim?',
                'jawaban' => 'Laporanmu langsung tercatat dengan status Baru dan diverifikasi oleh tim penanganan dengan target maksimal 1×24 jam kerja. Selanjutnya laporan ditindaklanjuti (Diproses), didalami bila diperlukan (Investigasi), hingga tuntas (Selesai). Setiap perubahan tahapan dapat kamu pantau kapan saja melalui halaman Cek Status Laporan.',
                'urutan' => 8,
            ],
            [
                'pertanyaan' => 'Bagaimana cara mengecek status laporan saya?',
                'jawaban' => 'Buka halaman Cek Status Laporan lalu masukkan 6 angka PIN akses yang kamu simpan saat melapor. Sistem akan menampilkan kode pengaduan, status terkini, linimasa progres penanganan, serta rincian laporanmu. Kamu tidak perlu membuat akun atau memasukkan data lain.',
                'urutan' => 9,
            ],
            [
                'pertanyaan' => 'Berapa lama laporan saya diproses?',
                'jawaban' => 'Verifikasi awal ditargetkan maksimal 1×24 jam kerja setelah laporan masuk. Lamanya penanganan selanjutnya bergantung pada kompleksitas kasus, kelengkapan bukti, dan ketersediaan pihak-pihak yang perlu dimintai keterangan. Kamu bisa memantau perkembangannya secara berkala melalui Cek Status Laporan.',
                'urutan' => 10,
            ],
            [
                'pertanyaan' => 'Saya bukan korban, tetapi saya menyaksikan kejadian. Bisakah saya yang melapor?',
                'jawaban' => 'Sangat bisa — dan itu sangat membantu. Pilih status pelapor Saksi saat mengisi formulir, lalu lengkapi data korban sesuai yang kamu ketahui. Identitas saksi dilindungi dengan cara yang sama seperti pelapor yang merupakan korban.',
                'urutan' => 11,
            ],
            [
                'pertanyaan' => 'Apa yang harus saya lakukan jika perundungan terjadi lagi setelah saya melapor?',
                'jawaban' => 'Buat laporan baru yang menjelaskan kejadian terbaru — kamu boleh menyebutkan bahwa kasus serupa sudah pernah kamu laporkan sebelumnya. Kejadian berulang memperkuat bukti dan dapat mempercepat penanganan. Namun jika keselamatanmu sedang terancam, jangan menunggu: segera hubungi pihak keamanan kampus atau layanan darurat 112.',
                'urutan' => 12,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::firstOrCreate(
                ['pertanyaan' => $faq['pertanyaan']],
                [
                    'jawaban' => $faq['jawaban'],
                    'urutan' => $faq['urutan'],
                    'is_active' => true,
                ]
            );
        }
    }
}
