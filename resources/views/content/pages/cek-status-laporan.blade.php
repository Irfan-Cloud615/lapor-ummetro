@php
    $configData = Helper::appClasses();
    $pageConfigs = ['myLayout' => 'horizontal'];
@endphp

@extends('layouts.layoutMaster')

@section('title', 'Cek Status Laporan')

@section('content')
    <!-- Intro halaman -->
    <div class="row">
        <div class="col-12 text-center mb-4">
            <span class="badge bg-label-primary px-3 py-2 mb-2">
                <i class="ti ti-file-search me-1"></i> Cek Status Laporan
            </span>
            <h3 class="fw-bold mb-2">Pantau Proses Penanganan Laporanmu</h3>
            <p class="text-muted mb-3 mx-auto" style="max-width: 40rem;">
                Masukkan <strong>PIN akses</strong> yang kamu simpan saat mengirim laporan untuk melihat status
                penanganannya. PIN hanya diketahui olehmu.
            </p>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-xl-8 mx-auto">

            <!-- Formulir cek status -->
            <div class="card mb-4" id="kartu-form">
                <div class="card-header">
                    <h5 class="mb-0">Formulir Cek Status</h5>
                    <small class="text-muted">Masukkan PIN yang diterima saat laporan selesai dikirim.</small>
                </div>
                <div class="card-body">
                    <form action="{{ route('pages-cek-status-laporan.check') }}" method="POST">
                        @csrf
                        <div class="row g-4">
                            <div class="col-12 col-md-6 col-lg-4 mx-auto text-center">
                                <label class="form-label" for="pin_akses">PIN Akses <span
                                        class="text-danger">*</span></label>
                                <input type="password" class="form-control font-monospace text-center" id="pin_akses"
                                    name="pin_akses" maxlength="6" inputmode="numeric" pattern="[0-9]{6}"
                                    placeholder="6 angka" autocomplete="off" required>
                                <small class="text-muted">6 angka, sama seperti yang tampil sekali saat laporan
                                    dikirim.</small>
                            </div>
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger d-flex mt-4 mb-0" role="alert">
                                <i class="ti ti-alert-circle me-2 mt-1"></i>
                                <div>{{ $errors->first() }}</div>
                            </div>
                        @endif

                        <div class="d-grid d-md-flex justify-content-md-center mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-search me-1"></i> Cek Status
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            @isset($pengaduan)
                <!-- Hasil pencarian -->
                <div class="card mb-4" id="kartu-hasil">
                    <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <small class="text-muted d-block">Kode Pengaduan</small>
                            <h5 class="mb-0 font-monospace">{{ $pengaduan->kode_pengaduan }}</h5>
                        </div>
                        <div class="text-end">
                            <span class="badge px-3 py-2 bg-label-{{ $statusMeta['warna'] }}">
                                <i class="ti {{ $statusMeta['ikon'] }} me-1"></i>{{ $statusMeta['label'] }}
                            </span>
                            <small class="text-muted d-block mt-1">Diperbarui
                                {{ $pengaduan->updated_at->format('d/m/Y H:i') }}</small>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-{{ $statusMeta['warna'] }} d-flex" role="alert">
                            <i class="ti {{ $statusMeta['ikon'] }} me-2 mt-1"></i>
                            <div>{{ $statusMeta['deskripsi'] }}</div>
                        </div>

                        <!-- Linimasa penanganan -->
                        <h6 class="fw-semibold mb-3">Progres Penanganan</h6>
                        <div class="mb-4">
                            @foreach ($tahapan as $tahap)
                                <div class="d-flex">
                                    <div class="d-flex flex-column align-items-center me-3">
                                        <span class="avatar avatar-sm">
                                            <span class="avatar-initial rounded-circle {{ $tahap['lingkaran'] }}">
                                                <i class="ti {{ $tahap['ikon'] }}"></i>
                                            </span>
                                        </span>
                                        @unless ($loop->last)
                                            <span class="border-start flex-grow-1" style="min-height: 1.5rem;"></span>
                                        @endunless
                                    </div>
                                    <div class="{{ $loop->last ? '' : 'pb-4' }}">
                                        <h6 class="mb-1 fw-semibold {{ $tahap['judul'] }}">{{ $tahap['label'] }}</h6>
                                        <small class="text-muted d-block">{{ $tahap['deskripsi'] }}</small>
                                        @if ($tahap['waktu'])
                                            <small class="text-muted d-block"><i
                                                    class="ti ti-clock me-1"></i>{{ $tahap['waktu'] }}</small>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Rincian laporan -->
                        <h6 class="fw-semibold mb-3">Rincian Laporan</h6>
                        <div class="row g-3">
                            <div class="col-12 col-md-4">
                                <div class="border rounded p-3 h-100">
                                    <small class="text-muted d-block">Kategori Kejadian</small>
                                    <span class="fw-semibold">{{ $pengaduan->kategori->nama_kategori ?? '—' }}</span>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="border rounded p-3 h-100">
                                    <small class="text-muted d-block">Waktu Kejadian</small>
                                    <span class="fw-semibold">
                                        {{ $pengaduan->tanggal_kejadian->format('d/m/Y') }},
                                        {{ substr((string) $pengaduan->waktu_kejadian, 0, 5) }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="border rounded p-3 h-100">
                                    <small class="text-muted d-block">Lokasi Kejadian</small>
                                    <span class="fw-semibold">{{ $pengaduan->lokasi_kejadian }}</span>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="border rounded p-3 h-100">
                                    <small class="text-muted d-block">Dilaporkan Pada</small>
                                    <span class="fw-semibold">{{ $pengaduan->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="border rounded p-3 h-100">
                                    <small class="text-muted d-block">Lampiran Bukti</small>
                                    <span class="fw-semibold">{{ $pengaduan->lampiran_bukti_count }} file</span>
                                </div>
                            </div>
                        </div>

                        <!-- Kronologi -->
                        <div class="mt-4">
                            <small class="text-muted d-block mb-1">Kronologi yang kamu laporkan</small>
                            <div class="border rounded p-3" style="white-space: pre-line;">{{ $pengaduan->kronologi }}</div>
                        </div>
                    </div>
                </div>
            @endisset

            <!-- Bantuan -->
            <div class="card">
                <div class="card-body d-flex">
                    <i class="ti ti-help-circle me-2 mt-1"></i>
                    <div>
                        <h6 class="fw-semibold mb-1">PIN hilang?</h6>
                        <p class="text-muted small mb-2">
                            PIN hanya ditampilkan sekali saat laporan dikirim dan tidak dapat dilihat kembali. Jika kamu
                            tidak lagi memilikinya, silakan hubungi tim penanganan perundungan kampus secara langsung.
                        </p>
                        <a href="{{ route('pages-lapor-perundungan') }}" class="small">
                            <i class="ti ti-plus me-1"></i>Buat laporan baru
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
