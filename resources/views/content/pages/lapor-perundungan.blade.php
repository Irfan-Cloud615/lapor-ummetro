@php
    $configData = Helper::appClasses();
    $pageConfigs = ['myLayout' => 'horizontal'];
@endphp

@extends('layouts.layoutMaster')

@section('title', 'Lapor Perundungan')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bs-stepper/bs-stepper.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/toastr/toastr.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/bs-stepper/bs-stepper.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/toastr/toastr.js') }}"></script>
@endsection

@section('page-script')
    <script src="{{ asset('js/pages/lapor-perundungan.js') }}"></script>
@endsection

@section('page-style')
    <style>
        /* Tampilan kode pengaduan & PIN pada kartu sukses */
        #kartu-berhasil .kode-display {
            font-size: 1.4rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;
        }
    </style>
@endsection

@section('content')
    <!-- Intro halaman -->
    <div class="row">
        <div class="col-12 text-center mb-4">
            <span class="badge bg-label-primary px-3 py-2 mb-2">
                <i class="ti ti-message-report me-1"></i> Lapor Perundungan
            </span>
            <h3 class="fw-bold mb-2">Formulir Pengaduan Perundungan</h3>
            <p class="text-muted mb-3 mx-auto" style="max-width: 40rem;">
                Isi formulir di bawah dengan jujur — kamu boleh menggunakan nama samaran.
                Seluruh isi laporan bersifat rahasia dan hanya dapat diakses oleh tim penanganan resmi.
            </p>
            <div class="d-flex justify-content-center flex-wrap gap-2">
                <span class="badge rounded-pill bg-label-success"><i class="ti ti-user-off me-1"></i> Bisa Anonim</span>
                <span class="badge rounded-pill bg-label-info"><i class="ti ti-shield-lock me-1"></i> Data Rahasia</span>
                <span class="badge rounded-pill bg-label-warning"><i class="ti ti-clipboard-check me-1"></i> Pasti
                    Ditindaklanjuti</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-xl-10 mx-auto">

            <!-- Kartu formulir (wizard) -->
            <div class="card mb-4" id="kartu-form">
                <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h5 class="mb-0">Formulir Pengaduan</h5>
                        <small class="text-muted">Kolom bertanda <span class="text-danger">*</span> wajib diisi.</small>
                    </div>
                    <span class="badge bg-label-warning">
                        <i class="ti ti-eye-off me-1"></i> Laporan dirahasiakan
                    </span>
                </div>
                <div class="card-body">
                    <form id="form-lapor" action="{{ route('pages-lapor-perundungan.store') }}" method="POST"
                        enctype="multipart/form-data" novalidate>
                        @csrf

                        <div class="bs-stepper" id="wizard-lapor">
                            <div class="bs-stepper-header">
                                <div class="step" data-target="#step-pelapor">
                                    <button type="button" class="step-trigger">
                                        <span class="bs-stepper-circle">1</span>
                                        <span class="bs-stepper-label">Kontak Pelapor</span>
                                    </button>
                                </div>
                                <div class="bs-stepper-line"></div>
                                <div class="step" data-target="#step-kejadian">
                                    <button type="button" class="step-trigger">
                                        <span class="bs-stepper-circle">2</span>
                                        <span class="bs-stepper-label">Detail Kejadian</span>
                                    </button>
                                </div>
                                <div class="bs-stepper-line"></div>
                                <div class="step" data-target="#step-bukti">
                                    <button type="button" class="step-trigger">
                                        <span class="bs-stepper-circle">3</span>
                                        <span class="bs-stepper-label">Bukti &amp; Kirim</span>
                                    </button>
                                </div>
                            </div>

                            <div class="bs-stepper-content">

                                <!-- Langkah 1 : Kontak Pelapor -->
                                <div id="step-pelapor" class="bs-stepper-pane">
                                    <!-- Hidden default inputs -->
                                    <input type="hidden" name="nama_pelapor" value="Anonim">
                                    <input type="hidden" name="status_pelapor" value="Lainnya">
                                    <input type="hidden" name="nama_korban" value="Anonim">
                                    <input type="hidden" name="nama_terlapor" value="Tidak Diketahui">

                                    <div class="row g-4">
                                        <div class="col-12 col-md-8 mx-auto">
                                            <label class="form-label" for="kontak_wa">Nomor WhatsApp <span
                                                    class="text-danger">*</span></label>
                                            <input type="tel" class="form-control" id="kontak_wa" name="kontak_wa"
                                                maxlength="20" inputmode="tel" data-label="Nomor WhatsApp"
                                                placeholder="08xxxxxxxxxx" required>
                                            <small class="text-muted">Hanya digunakan tim untuk konfirmasi rahasia dan
                                                penyampaian update laporan.</small>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end mt-4">
                                        <button type="button" class="btn btn-primary btn-next">
                                            Lanjut <i class="ti ti-arrow-right ms-1"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Langkah 3 : Detail Kejadian -->
                                <div id="step-kejadian" class="bs-stepper-pane">
                                    <div class="row g-4">
                                        <div class="col-12">
                                            <label class="form-label" for="kategori_id">Kategori Kejadian <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select select2" id="kategori_id" name="kategori_id"
                                                data-label="Kategori kejadian" data-placeholder="Pilih kategori kejadian"
                                                required>
                                                <option value="" selected></option>
                                                @foreach ($kategoris as $kategori)
                                                    <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @if ($kategoris->isEmpty())
                                                <small class="text-danger d-block mt-1">Kategori belum tersedia, silakan
                                                    hubungi admin.</small>
                                            @endif
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label class="form-label" for="tanggal_kejadian">Tanggal Kejadian <span
                                                    class="text-danger">*</span></label>
                                            <input type="date" class="form-control" id="tanggal_kejadian"
                                                name="tanggal_kejadian" data-label="Tanggal kejadian"
                                                max="{{ now()->toDateString() }}" required>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label class="form-label" for="waktu_kejadian">Waktu Kejadian <span
                                                    class="text-danger">*</span></label>
                                            <input type="time" class="form-control" id="waktu_kejadian"
                                                name="waktu_kejadian" data-label="Waktu kejadian" required>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label" for="lokasi_kejadian">Lokasi Kejadian <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="lokasi_kejadian"
                                                name="lokasi_kejadian" maxlength="255" data-label="Lokasi kejadian"
                                                placeholder="Contoh: Kantin Gedung A, parkiran, grup chat WhatsApp"
                                                required>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label" for="kronologi">Kronologi Kejadian <span
                                                    class="text-danger">*</span></label>
                                            <textarea class="form-control" id="kronologi" name="kronologi" rows="4" minlength="20"
                                                data-label="Kronologi kejadian" placeholder="Ceritakan urutan kejadian selengkap mungkin" required></textarea>
                                            <small class="text-muted">Minimal 20 karakter. Semakin detail, semakin mudah
                                                ditindaklanjuti.</small>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label" for="saksi">Saksi (Opsional)</label>
                                            <textarea class="form-control" id="saksi" name="saksi" rows="2"
                                                placeholder="Nama atau keterangan saksi yang melihat kejadian"></textarea>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between mt-4">
                                        <button type="button" class="btn btn-outline-secondary btn-prev">
                                            <i class="ti ti-arrow-left me-1"></i> Kembali
                                        </button>
                                        <button type="button" class="btn btn-primary btn-next">
                                            Lanjut <i class="ti ti-arrow-right ms-1"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Langkah 4 : Bukti & Kirim -->
                                <div id="step-bukti" class="bs-stepper-pane">
                                    <div class="row g-4">
                                        <div class="col-12">
                                            <label class="form-label" for="bukti">Unggah Bukti (Opsional)</label>
                                            <input type="file" class="form-control" id="bukti" name="bukti[]"
                                                multiple accept=".jpg,.jpeg,.png,.webp,.pdf,.doc,.docx,.mp4">
                                            <small class="text-muted">
                                                Maks. 5 file, tiap file maks. 5 MB. Format: jpg, jpeg, png, webp, pdf, doc,
                                                docx, mp4.
                                            </small>
                                            <ul class="list-unstyled small mt-2 mb-0" id="daftar-bukti"></ul>
                                        </div>
                                        <div class="col-12">
                                            <div class="alert alert-info d-flex mb-0" role="alert">
                                                <i class="ti ti-info-circle me-2 mt-1"></i>
                                                <div>
                                                    Setelah laporan terkirim, sistem akan membuatkan <strong>Kode
                                                        Pengaduan</strong> dan
                                                    <strong>PIN Akses</strong> secara otomatis. Simpan <strong>PIN
                                                        Akses</strong>-nya baik-baik
                                                    karena dibutuhkan untuk melihat status pengaduanmu nanti.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between mt-4">
                                        <button type="button" class="btn btn-outline-secondary btn-prev">
                                            <i class="ti ti-arrow-left me-1"></i> Kembali
                                        </button>
                                        <button type="submit" id="btn-kirim" class="btn btn-primary">
                                            <i class="ti ti-send me-1"></i> Kirim Pengaduan
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Kartu sukses : kode pengaduan & PIN -->
            <div class="card mb-4 d-none" id="kartu-berhasil">
                <div class="card-body text-center py-5 px-4">
                    <span class="avatar avatar-xl mb-3">
                        <span class="avatar-initial rounded-circle bg-label-success"><i
                                class="ti ti-circle-check"></i></span>
                    </span>
                    <h4 class="fw-bold mb-1">Pengaduan Berhasil Dikirim</h4>
                    <p class="text-muted mb-4">
                        Laporanmu telah kami terima dan akan segera diproses.<br>
                        Simpan <strong>Kode Pengaduan</strong> dan <strong>PIN</strong> di bawah ini.
                    </p>

                    <div class="row justify-content-center g-3 mb-4">
                        <div class="col-12 col-md-6">
                            <div class="card h-100 mb-0">
                                <div class="card-body py-3">
                                    <small class="text-muted d-block mb-1">Kode Pengaduan</small>
                                    <div class="d-flex align-items-center justify-content-center gap-2 flex-wrap">
                                        <span class="kode-display" id="hasil-kode">—</span>
                                        <button type="button" class="btn btn-icon btn-label-primary btn-copy"
                                            data-copy="kode" aria-label="Salin kode pengaduan">
                                            <i class="ti ti-copy"></i>
                                        </button>
                                    </div>
                                    <small class="text-muted">identitas laporanmu</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="card h-100 mb-0">
                                <div class="card-body py-3">
                                    <small class="text-muted d-block mb-1">PIN Akses</small>
                                    <div class="d-flex align-items-center justify-content-center gap-2 flex-wrap">
                                        <span class="kode-display" id="hasil-pin">—</span>
                                        <button type="button" class="btn btn-icon btn-label-primary btn-copy"
                                            data-copy="pin" aria-label="Salin PIN akses">
                                            <i class="ti ti-copy"></i>
                                        </button>
                                    </div>
                                    <small class="text-muted">untuk melihat status pengaduan</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-warning text-start d-flex mb-4" role="alert">
                        <i class="ti ti-alert-triangle me-2 mt-1"></i>
                        <div>
                            <strong>Catat &amp; simpan PIN ini baik-baik.</strong> PIN hanya ditampilkan <strong>sekali ini
                                saja</strong>
                            dan tidak dapat dilihat kembali — jangan bagikan kepada siapa pun. PIN digunakan nanti di
                            menu <strong>Cek Status Pengaduan</strong> untuk memantau proses penanganan laporanmu.
                        </div>
                    </div>

                    <div class="d-flex justify-content-center flex-wrap gap-2">
                        <a href="{{ route('pages-cek-status-laporan') }}" class="btn btn-primary">
                            <i class="ti ti-file-search me-1"></i> Cek Status Pengaduan
                        </a>
                        <button type="button" class="btn btn-label-secondary" id="btn-lapor-lagi">
                            <i class="ti ti-plus me-1"></i> Buat Laporan Baru
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
