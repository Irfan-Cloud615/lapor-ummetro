@php
    $configData = Helper::appClasses();
    $pageConfigs = ['myLayout' => 'horizontal'];
@endphp

@extends('layouts.layoutMaster')

@section('title', 'FAQ')

@section('page-style')
    <style>
        /* Jarak antar item accordion */
        #accordionFaq .accordion-item {
            margin-bottom: 0.25rem;
            overflow: hidden;
            transition: box-shadow 0.3s ease, border-color 0.3s ease;
        }

        #accordionFaq .accordion-item:last-child {
            margin-bottom: 0;
        }

        /* Reset margin-top bawaan Bootstrap accordion-item + accordion-item */
        #accordionFaq .accordion-item+.accordion-item {
            margin-top: 0;
        }

        /* Animasi halus (smooth cubic-bezier transition) saat membuka & menutup accordion */
        #accordionFaq .accordion-collapse.collapsing {
            transition: height 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        }

        #accordionFaq .accordion-collapse.collapse {
            transition: height 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Smooth transition untuk tombol dan ikon rotasi panah accordion */
        #accordionFaq .accordion-button {
            transition: background-color 0.3s ease, color 0.3s ease, box-shadow 0.3s ease;
        }

        #accordionFaq .accordion-button::after {
            transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Hover effect halus pada item accordion */
        #accordionFaq .accordion-item:hover {
            box-shadow: 0 0.25rem 0.75rem rgba(0, 0, 0, 0.06);
        }
    </style>
@endsection

@section('content')
    <!-- Intro halaman -->
    <div class="row">
        <div class="col-12 text-center mb-4">
            <span class="badge bg-label-primary px-3 py-2 mb-2">
                <i class="ti ti-help me-1"></i> FAQ
            </span>
            <h3 class="fw-bold mb-2">Pertanyaan yang Sering Diajukan</h3>
            <p class="text-muted mb-3 mx-auto" style="max-width: 40rem;">
                Belum paham cara kerja layanan pengaduan perundungan? Temukan jawaban atas
                pertanyaan yang paling sering ditanyakan pelapor di bawah ini.
            </p>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-xl-8 mx-auto">

            @if ($faqs->isNotEmpty())
                <!-- Pencarian pertanyaan -->
                <div class="mb-4">
                    <div class="input-group">
                        <input type="text" class="form-control" id="cari-faq"
                            placeholder="Cari pertanyaan, misalnya: PIN akses" autocomplete="off"
                            aria-label="Cari pertanyaan">
                    </div>
                </div>

                <!-- Daftar pertanyaan -->
                <div class="accordion" id="accordionFaq">
                    @foreach ($faqs as $faq)
                        <div class="accordion-item faq-item">
                            <h2 class="accordion-header" id="faqHeading{{ $loop->iteration }}">
                                <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#faqCollapse{{ $loop->iteration }}"
                                    aria-expanded="{{ $loop->first ? 'true' : 'false' }}"
                                    aria-controls="faqCollapse{{ $loop->iteration }}">
                                    {{ $faq->pertanyaan }}
                                </button>
                            </h2>
                            <div id="faqCollapse{{ $loop->iteration }}"
                                class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}"
                                data-bs-parent="#accordionFaq">
                                <div class="accordion-body">
                                    {{ $faq->jawaban }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pencarian tidak ditemukan -->
                <div class="alert alert-primary d-flex mt-3 mb-0 d-none" id="faq-tidak-ditemukan" role="alert">
                    <i class="ti ti-alert-circle me-2 mt-1"></i>
                    <div>Tidak ada pertanyaan yang cocok dengan pencarianmu. Coba kata kunci lain, atau hubungi tim
                        penanganan secara langsung.</div>
                </div>
            @else
                <!-- Belum ada FAQ -->
                <div class="card">
                    <div class="card-body text-center py-5">
                        <span class="avatar avatar-xl mb-3">
                            <span class="avatar-initial rounded-circle bg-label-primary"><i
                                    class="ti ti-help-circle"></i></span>
                        </span>
                        <h5 class="fw-semibold mb-1">Belum Ada Pertanyaan</h5>
                        <p class="text-muted mb-0">Daftar pertanyaan yang sering diajukan sedang disiapkan. Silakan kunjungi
                            halaman ini kembali nanti.</p>
                    </div>
                </div>
            @endif

            <!-- Bantuan lanjutan -->
            <div class="card mt-4 mb-4">
                <div class="card-body">
                    <div class="text-center mb-3">
                        <h5 class="fw-semibold mb-1">Masih Ada yang Ingin Ditanyakan?</h5>
                        <p class="text-muted mb-0">Langsung saja — kirim laporan baru atau pantau laporan yang sudah kamu
                            kirim.</p>
                    </div>
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <a href="{{ route('pages-lapor-perundungan') }}"
                                class="d-block h-100 text-decoration-none border rounded p-3">
                                <div class="d-flex align-items-center">
                                    <span class="avatar flex-shrink-0">
                                        <span class="avatar-initial rounded-circle bg-label-primary"><i
                                                class="ti ti-message-report"></i></span>
                                    </span>
                                    <div class="ms-3">
                                        <h6 class="mb-1 fw-semibold">Buat Laporan</h6>
                                        <small class="text-muted d-block">Sampaikan kejadian yang kamu alami atau saksikan
                                            secara rahasia.</small>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-12 col-md-6">
                            <a href="{{ route('pages-cek-status-laporan') }}"
                                class="d-block h-100 text-decoration-none border rounded p-3">
                                <div class="d-flex align-items-center">
                                    <span class="avatar flex-shrink-0">
                                        <span class="avatar-initial rounded-circle bg-label-primary"><i
                                                class="ti ti-file-search"></i></span>
                                    </span>
                                    <div class="ms-3">
                                        <h6 class="mb-1 fw-semibold">Cek Status Laporan</h6>
                                        <small class="text-muted d-block">Pantau tahapan penanganan laporanmu dengan PIN
                                            akses.</small>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <hr class="my-4">
                    <div class="d-flex flex-column flex-sm-row align-items-center justify-content-center gap-2 text-center">
                        <span class="avatar avatar-sm flex-shrink-0">
                            <span class="avatar-initial rounded-circle bg-label-danger"><i class="ti ti-sos"></i></span>
                        </span>
                        <p class="text-muted mb-0 small">
                            Sedang dalam bahaya? Jangan menunggu laporan diproses — segera hubungi pihak keamanan kampus
                            atau layanan darurat
                            <a href="tel:112" class="fw-semibold">112</a>.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('page-script')
    <script>
        // Saring daftar FAQ sesuai kata kunci pencarian
        var inputCari = document.getElementById('cari-faq');

        if (inputCari) {
            inputCari.addEventListener('input', function() {
                var kataKunci = this.value.trim().toLowerCase();
                var jumlahTampil = 0;

                document.querySelectorAll('.faq-item').forEach(function(item) {
                    var cocok = item.textContent.toLowerCase().includes(kataKunci);
                    item.classList.toggle('d-none', !cocok);

                    if (cocok) {
                        jumlahTampil++;
                    }
                });

                document.getElementById('faq-tidak-ditemukan').classList.toggle('d-none', jumlahTampil > 0);
            });
        }
    </script>
@endsection
