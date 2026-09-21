@php
    $configData = Helper::appClasses();
    $pageConfigs = ['myLayout' => 'horizontal'];
@endphp


@extends('layouts.layoutMaster')

@section('title', 'Beranda')

@section('page-style')
    <style>
        /* Anchor offset so sections are not hidden behind the navbar */
        .beranda section[id] {
            scroll-margin-top: 6rem;
        }

        /* Hero */
        .beranda .hero-beranda {
            position: relative;
            overflow: hidden;
            border-radius: 0.5rem;
            background: linear-gradient(130deg, #7367f0 0%, #655bd8 50%, #5147c1 100%);
        }

        .beranda .hero-beranda::before,
        .beranda .hero-beranda::after {
            content: "";
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.08);
        }

        .beranda .hero-beranda::before {
            width: 26rem;
            height: 26rem;
            top: -10rem;
            right: -8rem;
        }

        .beranda .hero-beranda::after {
            width: 16rem;
            height: 16rem;
            bottom: -8rem;
            left: 32%;
            background: rgba(255, 255, 255, 0.05);
        }

        .beranda .hero-content,
        .beranda .hero-visual {
            position: relative;
            z-index: 1;
        }

        .beranda .hero-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.45rem 1rem;
            border-radius: 2rem;
            background: rgba(255, 255, 255, 0.16);
            border: 1px solid rgba(255, 255, 255, 0.32);
            color: #fff;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .beranda .hero-title {
            font-size: clamp(1.9rem, 1.35rem + 2.2vw, 2.75rem);
            font-weight: 700;
            line-height: 1.25;
            color: #fff;
        }

        .beranda .hero-text {
            color: rgba(255, 255, 255, 0.85);
            font-size: 1.05rem;
            max-width: 34rem;
        }

        .beranda .hero-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.45rem 0.95rem;
            border-radius: 2rem;
            background: rgba(255, 255, 255, 0.14);
            border: 1px solid rgba(255, 255, 255, 0.26);
            color: #fff;
            font-size: 0.85rem;
            backdrop-filter: blur(3px);
        }

        .beranda .hero-chip i {
            font-size: 1.05rem;
        }

        .beranda .btn-hero-primary {
            background: #fff;
            color: var(--bs-primary);
            font-weight: 600;
        }

        .beranda .btn-hero-primary:hover,
        .beranda .btn-hero-primary:focus {
            background: rgba(255, 255, 255, 0.85);
            color: var(--bs-primary);
        }

        .beranda .btn-hero-outline {
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.55);
            background: transparent;
            font-weight: 500;
        }

        .beranda .btn-hero-outline:hover,
        .beranda .btn-hero-outline:focus {
            background: rgba(255, 255, 255, 0.14);
            border-color: #fff;
            color: #fff;
        }

        /* Floating badges around the hero card */
        .beranda .float-badge {
            position: absolute;
            z-index: 2;
        }

        .beranda .float-badge-top {
            top: -1.4rem;
            right: -0.6rem;
        }

        .beranda .float-badge-bottom {
            bottom: -1.4rem;
            left: -0.6rem;
        }

        /* Hover effect for feature cards */
        .beranda .hover-lift {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .beranda .hover-lift:hover {
            transform: translateY(-5px);
        }

        /* Report flow step numbers */
        .beranda .step-number {
            width: 2.9rem;
            height: 2.9rem;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(var(--bs-primary-rgb), 0.12);
            color: var(--bs-primary);
            font-weight: 700;
            font-size: 1.15rem;
            border: 2px solid rgba(var(--bs-primary-rgb), 0.35);
        }

        /* Commitment strip dividers */
        @media (min-width: 768px) {
            .beranda .strip-item+.strip-item {
                border-left: 1px dashed var(--bs-border-color);
            }
        }

        /* Final call to action */
        .beranda .cta-beranda {
            position: relative;
            overflow: hidden;
            border-radius: 0.5rem;
            background: linear-gradient(130deg, #5147c1 0%, #655bd8 55%, #7367f0 100%);
            padding: 3rem 1.5rem;
        }

        .beranda .cta-beranda::before {
            content: "";
            position: absolute;
            width: 20rem;
            height: 20rem;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.07);
            top: -9rem;
            left: -7rem;
        }

        .beranda .cta-beranda::after {
            content: "";
            position: absolute;
            width: 14rem;
            height: 14rem;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.05);
            bottom: -7rem;
            right: -5rem;
        }

        .beranda .cta-content {
            position: relative;
            z-index: 1;
        }
    </style>
@endsection

@section('content')
    <div class="beranda">

        <!-- Hero -->
        <section class="hero-beranda mb-5">
            <div class="hero-content p-4 p-md-5">
                <div class="row align-items-center g-5">
                    <div class="col-lg-7">
                        <span class="hero-eyebrow mb-3">
                            <i class="ti ti-speakerphone"></i>
                            Layanan Resmi Pelaporan Perundungan — Universitas Muhammadiyah Metro
                        </span>
                        <h1 class="hero-title mb-3">Kamu Tidak Sendirian.<br>Ceritakan, Kami Lindungi.</h1>
                        <p class="hero-text mb-4">
                            Sistem pelaporan perundungan yang aman dan rahasia untuk civitas akademika Universitas
                            Muhammadiyah Metro. Laporkan kejadian yang kamu alami
                            atau kamu saksikan — identitasmu tersembunyi, dan setiap laporan pasti ditindaklanjuti
                            oleh tim penanganan Universitas Muhammadiyah Metro.
                        </p>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('pages-lapor-perundungan') }}" class="btn btn-hero-primary btn-lg">
                                <i class="ti ti-message-report me-1"></i> Buat Laporan Sekarang
                            </a>
                            <a href="#alur-laporan" class="btn btn-hero-outline btn-lg">Lihat Cara Kerjanya</a>
                        </div>
                        <div class="d-flex flex-wrap gap-2 mt-4">
                            <span class="hero-chip"><i class="ti ti-user-off"></i> 100% Anonim</span>
                            <span class="hero-chip"><i class="ti ti-shield-lock"></i> Kerahasiaan Terjamin</span>
                            <span class="hero-chip"><i class="ti ti-circle-check"></i> Pasti Ditindaklanjuti</span>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="hero-visual px-lg-3">
                            <!-- Floating badge: anonymity -->
                            <div class="float-badge float-badge-top card border-0 shadow d-none d-lg-block">
                                <div class="card-body p-2 px-3 d-flex align-items-center gap-2">
                                    <span class="avatar avatar-sm">
                                        <span class="avatar-initial rounded-circle bg-label-primary"><i
                                                class="ti ti-user-off"></i></span>
                                    </span>
                                    <div>
                                        <span class="fw-semibold d-block" style="font-size: 0.85rem;">100% Anonim</span>
                                        <small class="text-muted">Tanpa nama pun bisa</small>
                                    </div>
                                </div>
                            </div>
                            <!-- Floating badge: mentoring -->
                            <div class="float-badge float-badge-bottom card border-0 shadow d-none d-lg-block">
                                <div class="card-body p-2 px-3 d-flex align-items-center gap-2">
                                    <span class="avatar avatar-sm">
                                        <span class="avatar-initial rounded-circle bg-label-success"><i
                                                class="ti ti-heart-handshake"></i></span>
                                    </span>
                                    <div>
                                        <span class="fw-semibold d-block" style="font-size: 0.85rem;">Pendampingan
                                            Konseling</span>
                                        <small class="text-muted">Korban didampingi hingga tuntas</small>
                                    </div>
                                </div>
                            </div>
                            <!-- Sample report status card -->
                            <div class="card border-0 shadow-lg">
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <small class="text-muted d-block mb-1">Nomor Laporan</small>
                                            <span class="fw-semibold">#LPR-2026-0047</span>
                                        </div>
                                        <span class="badge bg-label-success">Terverifikasi</span>
                                    </div>
                                    <hr class="my-3">
                                    <div class="d-flex align-items-center mb-3">
                                        <span class="avatar avatar-sm me-3 flex-shrink-0">
                                            <span class="avatar-initial rounded-circle bg-label-success"><i
                                                    class="ti ti-circle-check"></i></span>
                                        </span>
                                        <div>
                                            <span class="d-block fw-semibold" style="font-size: 0.9rem;">Laporan
                                                Terkirim</span>
                                            <small class="text-muted">Senin, 08.30 WIB</small>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center mb-3">
                                        <span class="avatar avatar-sm me-3 flex-shrink-0">
                                            <span class="avatar-initial rounded-circle bg-label-success"><i
                                                    class="ti ti-circle-check"></i></span>
                                        </span>
                                        <div>
                                            <span class="d-block fw-semibold" style="font-size: 0.9rem;">Diverifikasi Tim
                                                Penanganan</span>
                                            <small class="text-muted">Senin, 10.15 WIB</small>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="avatar avatar-sm me-3 flex-shrink-0">
                                            <span class="avatar-initial rounded-circle bg-label-primary"><i
                                                    class="ti ti-clipboard-check"></i></span>
                                        </span>
                                        <div class="flex-grow-1">
                                            <span class="d-block fw-semibold" style="font-size: 0.9rem;">Sedang
                                                Ditangani</span>
                                            <small class="text-muted">Proses berjalan</small>
                                        </div>
                                        <span class="badge bg-label-primary">Berjalan</span>
                                    </div>
                                    <hr class="my-3">
                                    <div class="d-flex align-items-center">
                                        <span class="avatar avatar-sm me-3 flex-shrink-0">
                                            <span class="avatar-initial rounded-circle bg-label-primary"><i
                                                    class="ti ti-eye-off"></i></span>
                                        </span>
                                        <div>
                                            <span class="d-block fw-semibold" style="font-size: 0.9rem;">Identitas pelapor
                                                disembunyikan</span>
                                            <small class="text-muted">Nama &amp; data pribadi tidak pernah
                                                ditampilkan</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Trust commitments -->
        <section class="mb-5">
            <div class="text-center mb-4">
                <span class="badge bg-label-primary px-3 py-2 mb-2">Komitmen Kami</span>
                <h3 class="fw-bold mb-1">Kenapa Kamu Aman Melapor di Sini</h3>
                <p class="text-muted mb-0">Komitmen Universitas Muhammadiyah Metro untuk perlindungan civitas akademika.</p>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-xl-3">
                    <div class="card h-100 hover-lift">
                        <div class="card-body text-center">
                            <span class="avatar avatar-lg mb-3">
                                <span class="avatar-initial rounded-circle bg-label-primary"><i
                                        class="ti ti-user-off"></i></span>
                            </span>
                            <h6 class="fw-semibold">Anonim Sepenuhnya</h6>
                            <p class="text-muted small mb-0">
                                Kamu boleh melapor tanpa mencantumkan nama. Tidak ada yang bisa melihat siapa pelapornya.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="card h-100 hover-lift">
                        <div class="card-body text-center">
                            <span class="avatar avatar-lg mb-3">
                                <span class="avatar-initial rounded-circle bg-label-success"><i
                                        class="ti ti-shield-lock"></i></span>
                            </span>
                            <h6 class="fw-semibold">Kerahasiaan Terjamin</h6>
                            <p class="text-muted small mb-0">
                                Isi laporan hanya bisa diakses tim penanganan yang berwenang — tidak dibagikan ke siapa pun.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="card h-100 hover-lift">
                        <div class="card-body text-center">
                            <span class="avatar avatar-lg mb-3">
                                <span class="avatar-initial rounded-circle bg-label-warning"><i
                                        class="ti ti-clipboard-check"></i></span>
                            </span>
                            <h6 class="fw-semibold">Pasti Ditindaklanjuti</h6>
                            <p class="text-muted small mb-0">
                                Setiap laporan diverifikasi dan ditangani sesuai prosedur. Tidak ada laporan yang diabaikan.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="card h-100 hover-lift">
                        <div class="card-body text-center">
                            <span class="avatar avatar-lg mb-3">
                                <span class="avatar-initial rounded-circle bg-label-info"><i
                                        class="ti ti-heart-handshake"></i></span>
                            </span>
                            <h6 class="fw-semibold">Pendampingan Korban</h6>
                            <p class="text-muted small mb-0">
                                Tim Konseling &amp; Pendampingan UM Metro siap mendampingi korban selama proses penanganan
                                hingga merasa aman kembali.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Commitment strip -->
        <section class="mb-5">
            <div class="card">
                <div class="card-body py-4">
                    <div class="row g-4 text-center">
                        <div class="col-md-4 strip-item">
                            <div class="d-flex justify-content-center align-items-center mb-2">
                                <span class="avatar avatar-sm me-2">
                                    <span class="avatar-initial rounded-circle bg-label-primary"><i
                                            class="ti ti-file-search"></i></span>
                                </span>
                                <h3 class="fw-bold text-primary mb-0">&le; 1&times;24 Jam</h3>
                            </div>
                            <span class="text-muted">Target verifikasi awal setiap laporan</span>
                        </div>
                        <div class="col-md-4 strip-item">
                            <div class="d-flex justify-content-center align-items-center mb-2">
                                <span class="avatar avatar-sm me-2">
                                    <span class="avatar-initial rounded-circle bg-label-success"><i
                                            class="ti ti-lock"></i></span>
                                </span>
                                <h3 class="fw-bold text-success mb-0">100%</h3>
                            </div>
                            <span class="text-muted">Kerahasiaan identitas pelapor dijaga</span>
                        </div>
                        <div class="col-md-4 strip-item">
                            <div class="d-flex justify-content-center align-items-center mb-2">
                                <span class="avatar avatar-sm me-2">
                                    <span class="avatar-initial rounded-circle bg-label-danger"><i
                                            class="ti ti-shield-check"></i></span>
                                </span>
                                <h3 class="fw-bold text-danger mb-0">0</h3>
                            </div>
                            <span class="text-muted">Data laporan yang dibagikan ke pihak lain</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Report flow -->
        <section id="alur-laporan" class="mb-5">
            <div class="text-center mb-4">
                <span class="badge bg-label-primary px-3 py-2 mb-2">Alur Pelaporan</span>
                <h3 class="fw-bold mb-1">Melapor Itu Mudah</h3>
                <p class="text-muted mb-0">Empat langkah sederhana. Kamu bisa berhenti kapan saja — dan tetap terlindungi.
                </p>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-xl-3">
                    <div class="card h-100 hover-lift">
                        <div class="card-body text-center">
                            <span class="step-number mb-3">1</span>
                            <h6 class="fw-semibold mt-3">Ceritakan Kejadianmu</h6>
                            <p class="text-muted small mb-0">
                                Isi formulir laporan: apa yang terjadi, kapan, dan di mana. Lampirkan bukti bila ada.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="card h-100 hover-lift">
                        <div class="card-body text-center">
                            <span class="step-number mb-3">2</span>
                            <h6 class="fw-semibold mt-3">Pilih Tetap Anonim</h6>
                            <p class="text-muted small mb-0">
                                Gunakan nama samaran atau tanpa nama sama sekali. Identitas aslimu terkunci rapat.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="card h-100 hover-lift">
                        <div class="card-body text-center">
                            <span class="step-number mb-3">3</span>
                            <h6 class="fw-semibold mt-3">Tim Memverifikasi</h6>
                            <p class="text-muted small mb-0">
                                Laporan diperiksa tim penanganan Universitas Muhammadiyah Metro — target verifikasi awal
                                maksimal 1&times;24 jam kerja.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="card h-100 hover-lift">
                        <div class="card-body text-center">
                            <span class="step-number mb-3">4</span>
                            <h6 class="fw-semibold mt-3">Penanganan &amp; Pendampingan</h6>
                            <p class="text-muted small mb-0">
                                Kasus ditangani sesuai prosedur dan korban didampingi. Pantau status lewat menu Cek Status
                                Laporan.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Types of bullying -->
        <section id="bentuk-perundungan" class="mb-5">
            <div class="text-center mb-4">
                <span class="badge bg-label-primary px-3 py-2 mb-2">Kenali Bentuknya</span>
                <h3 class="fw-bold mb-1">Apa Saja yang Bisa Dilaporkan?</h3>
                <p class="text-muted mb-0">Perundungan di lingkungan kampus tidak selalu berupa kekerasan fisik. Semua
                    bentuk berikut layak dilaporkan.</p>
            </div>
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card h-100 hover-lift">
                        <div class="card-body d-flex">
                            <span class="avatar avatar-lg me-3 flex-shrink-0">
                                <span class="avatar-initial rounded-circle bg-label-danger"><i
                                        class="ti ti-hand-stop"></i></span>
                            </span>
                            <div>
                                <h6 class="fw-semibold">Perundungan Fisik</h6>
                                <p class="text-muted small mb-0">
                                    Memukul, menendang, mendorong, meludahi, hingga merusak atau mengambil barang milikmu.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100 hover-lift">
                        <div class="card-body d-flex">
                            <span class="avatar avatar-lg me-3 flex-shrink-0">
                                <span class="avatar-initial rounded-circle bg-label-warning"><i
                                        class="ti ti-message-circle-off"></i></span>
                            </span>
                            <div>
                                <h6 class="fw-semibold">Perundungan Verbal</h6>
                                <p class="text-muted small mb-0">
                                    Mengejek, memberi julukan buruk, mengancam, mempermalukan, atau merendahkan dengan
                                    kata-kata.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100 hover-lift">
                        <div class="card-body d-flex">
                            <span class="avatar avatar-lg me-3 flex-shrink-0">
                                <span class="avatar-initial rounded-circle bg-label-info"><i
                                        class="ti ti-users"></i></span>
                            </span>
                            <div>
                                <h6 class="fw-semibold">Perundungan Sosial</h6>
                                <p class="text-muted small mb-0">
                                    Mengucilkan dari kelompok, menyebarkan rumor, atau memengaruhi orang lain untuk
                                    menjauhimu.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100 hover-lift">
                        <div class="card-body d-flex">
                            <span class="avatar avatar-lg me-3 flex-shrink-0">
                                <span class="avatar-initial rounded-circle bg-label-primary"><i
                                        class="ti ti-device-mobile-message"></i></span>
                            </span>
                            <div>
                                <h6 class="fw-semibold">Perundungan Siber</h6>
                                <p class="text-muted small mb-0">
                                    Komentar jahat, ancaman, fitnah, atau penyebaran aib melalui media sosial, grup chat
                                    civitas akademika, dan media digital.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex align-items-start gap-2 mt-4">
                <i class="ti ti-circle-check text-success mt-1"></i>
                <span class="text-muted small">
                    Ragu apakah kejadianmu termasuk perundungan? <strong>Tetap laporkan.</strong>
                    Tim kami akan membantu menilai — tidak ada laporan yang sia-sia.
                </span>
            </div>
        </section>

        <!-- FAQ -->
        <section id="faq" class="mb-5">
            <div class="text-center mb-4">
                <span class="badge bg-label-primary px-3 py-2 mb-2">Pertanyaan Umum</span>
                <h3 class="fw-bold mb-1">Masih Ragu? Ini Jawabannya</h3>
                <p class="text-muted mb-0">Hal-hal yang paling sering ditanyakan pelapor sebelum mengirim laporan
                    pertamanya.</p>
            </div>
            <div class="row justify-content-center">
                <div class="col-xl-9">
                    <div class="accordion" id="accordionFaq">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faqSatu">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseFaqSatu" aria-expanded="true"
                                    aria-controls="collapseFaqSatu">
                                    Apakah orang yang saya laporkan akan tahu siapa saya?
                                </button>
                            </h2>
                            <div id="collapseFaqSatu" class="accordion-collapse collapse show"
                                data-bs-parent="#accordionFaq">
                                <div class="accordion-body">
                                    Tidak. Identitas pelapor disembunyikan sepenuhnya dari terlapor maupun pihak lain.
                                    Hanya tim penanganan berwenang yang dapat mengakses data laporan, dan mereka terikat
                                    kewajiban untuk menjaga kerahasiaan.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faqDua">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseFaqDua" aria-expanded="false"
                                    aria-controls="collapseFaqDua">
                                    Bisakah saya melapor tanpa nama (anonim)?
                                </button>
                            </h2>
                            <div id="collapseFaqDua" class="accordion-collapse collapse" data-bs-parent="#accordionFaq">
                                <div class="accordion-body">
                                    Bisa. Kamu dapat mengirim laporan tanpa mencantumkan identitas apa pun.
                                    Laporan anonim tetap diverifikasi dan ditindaklanjuti dengan prosedur yang sama.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faqTiga">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseFaqTiga" aria-expanded="false"
                                    aria-controls="collapseFaqTiga">
                                    Apa yang terjadi setelah saya mengirim laporan?
                                </button>
                            </h2>
                            <div id="collapseFaqTiga" class="accordion-collapse collapse" data-bs-parent="#accordionFaq">
                                <div class="accordion-body">
                                    Tim penanganan memverifikasi laporanmu (target maksimal 1&times;24 jam kerja),
                                    mengumpulkan
                                    keterangan secara hati-hati, lalu menangani kasus sesuai regulasi Universitas
                                    Muhammadiyah Metro. Jika kamu adalah
                                    korban, tim layanan konseling/pendampingan akan menawarkan bantuan selama proses
                                    berlangsung.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faqEmpat">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseFaqEmpat" aria-expanded="false"
                                    aria-controls="collapseFaqEmpat">
                                    Saya melihat teman saya menjadi korban. Bisakah saya yang melapor?
                                </button>
                            </h2>
                            <div id="collapseFaqEmpat" class="accordion-collapse collapse"
                                data-bs-parent="#accordionFaq">
                                <div class="accordion-body">
                                    Sangat bisa — dan sangat membantu. Saksi dilindungi dengan cara yang sama: identitasmu
                                    dirahasiakan. Laporanmu bisa menjadi awal pertolongan untuk temanmu.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faqLima">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseFaqLima" aria-expanded="false"
                                    aria-controls="collapseFaqLima">
                                    Bagaimana saya memantau perkembangan laporan saya?
                                </button>
                            </h2>
                            <div id="collapseFaqLima" class="accordion-collapse collapse" data-bs-parent="#accordionFaq">
                                <div class="accordion-body">
                                    Setelah mengirim laporan, kamu akan menerima nomor laporan. Gunakan nomor tersebut pada
                                    menu <strong>Cek Status Laporan</strong> untuk melihat tahapan penanganan kapan saja.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Emergency help -->
        <section class="mb-5">
            <div class="card">
                <div class="card-body d-flex flex-column flex-md-row align-items-md-center gap-3 py-4">
                    <span class="avatar avatar-lg flex-shrink-0">
                        <span class="avatar-initial rounded-circle bg-label-danger"><i class="ti ti-sos"></i></span>
                    </span>
                    <div class="flex-grow-1 text-center text-md-start">
                        <h5 class="fw-semibold mb-1">Kamu sedang dalam bahaya?</h5>
                        <p class="text-muted mb-0">
                            Jika keselamatanmu terancam saat ini juga, jangan menunggu laporan diproses. Segera hubungi
                            pihak keamanan kampus, Dosen Pembimbing Akademik, atau layanan darurat.
                        </p>
                    </div>
                    <div class="d-flex flex-column flex-sm-row gap-2 flex-shrink-0">
                        <a href="tel:112" class="btn btn-danger"><i class="ti ti-phone-call me-1"></i> Darurat 112</a>
                        <a href="javascript:void(0);" class="btn btn-label-danger">Hubungi Satgas / Konseling</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Final call to action -->
        <section class="cta-beranda text-center">
            <div class="cta-content">
                <span class="avatar avatar-xl mb-3">
                    <span class="avatar-initial rounded-circle bg-white text-primary"><i
                            class="ti ti-message-report"></i></span>
                </span>
                <h3 class="fw-bold text-white mb-2">Jangan Biarkan Perundungan Berlanjut</h3>
                <p class="mx-auto mb-4" style="max-width: 34rem; color: rgba(255, 255, 255, 0.85);">
                    Suaramu berarti. Satu laporan dapat menciptakan lingkungan Universitas Muhammadiyah Metro yang aman,
                    inklusif, dan bebas dari perundungan.
                </p>
                <div class="d-flex justify-content-center flex-wrap gap-2">
                    <a href="{{ route('pages-lapor-perundungan') }}" class="btn btn-hero-primary btn-lg">
                        Buat Laporan Sekarang <i class="ti ti-arrow-right ms-1"></i>
                    </a>
                </div>
                <p class="mt-3 mb-0 small" style="color: rgba(255, 255, 255, 0.7);">
                    <i class="ti ti-lock me-1"></i>Aman &bull; Anonim &bull; Tanpa syarat apa pun
                </p>
            </div>
        </section>

    </div>
@endsection

@section('page-script')
    <script>
        // Smooth scrolling for in-page anchor links
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.beranda a[href^="#"]').forEach(function(el) {
                el.addEventListener('click', function(e) {
                    var target = document.querySelector(el.getAttribute('href'));
                    if (target) {
                        e.preventDefault();
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
        });
    </script>
@endsection
