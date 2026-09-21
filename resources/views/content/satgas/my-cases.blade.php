@extends('layouts/layoutMaster')

@section('title', 'Tugas Saya - Satgas Penanganan')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/katex.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/editor.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
@endsection

@section('page-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/app-email.css') }}" />
    <style>
        .email-list-item-kronologi {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 320px;
        }

        @media (max-width: 767.98px) {
            .email-list-item-kronologi {
                max-width: 180px;
            }
        }

        /* Activity Timeline Styling */
        .timeline {
            position: relative;
            list-style: none;
            padding-left: 1.5rem;
        }

        .timeline::before {
            content: '';
            position: absolute;
            top: 0.5rem;
            bottom: 0.5rem;
            left: 0.45rem;
            width: 2px;
            background-color: #e9ecef;
        }

        .timeline-item {
            position: relative;
            padding-bottom: 1.25rem;
        }

        .timeline-item:last-child {
            padding-bottom: 0;
        }

        .timeline-point {
            position: absolute;
            left: -1.5rem;
            top: 0.25rem;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: var(--bs-primary);
            border: 2px solid #fff;
            box-shadow: 0 0 0 2px var(--bs-primary);
            z-index: 1;
        }

        .timeline-point-success {
            background-color: var(--bs-success);
            box-shadow: 0 0 0 2px var(--bs-success);
        }

        .timeline-point-primary {
            background-color: var(--bs-primary);
            box-shadow: 0 0 0 2px var(--bs-primary);
        }

        .timeline-event {
            position: relative;
            width: 100%;
        }

        /* Smooth Accordion Transition Styling */
        .accordion-button {
            transition: all 0.3s ease-in-out;
            border-radius: 0.375rem !important;
        }

        .accordion-button:not(.collapsed) {
            background-color: rgba(var(--bs-primary-rgb), 0.06);
            color: var(--bs-primary);
            box-shadow: none;
        }

        .accordion-button:focus {
            box-shadow: none;
            border-color: rgba(var(--bs-primary-rgb), 0.25);
        }

        .accordion-collapse.collapsing {
            transition: height 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .accordion-collapse.collapse.show {
            transition: height 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        }
    </style>
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/quill/katex.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/quill/quill.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/block-ui/block-ui.js') }}"></script>
@endsection

@section('content')
    <div class="app-email card">
        <div class="row g-0">
            <!-- Email Sidebar -->
            <div class="col app-email-sidebar border-end flex-grow-0" id="app-email-sidebar">
                <div class="btn-compost-wrapper d-grid">
                    <button class="btn btn-primary btn-compose" type="button">
                        <i class="ti ti-briefcase me-1"></i> Tugas Satgas ({{ $cases->count() }})
                    </button>
                </div>
                <!-- Email Filters -->
                <div class="email-filters py-2">
                    <!-- Email Filters: Status Folders -->
                    <ul class="email-filter-folders list-unstyled mb-4">
                        <li class="active d-flex justify-content-between" data-target="inbox">
                            <a href="javascript:void(0);" class="d-flex flex-wrap align-items-center">
                                <i class="ti ti-mail"></i>
                                <span class="align-middle ms-2">Semua Laporan</span>
                            </a>
                            <div class="badge bg-label-primary rounded-pill badge-center">{{ $cases->count() }}</div>
                        </li>
                        <li class="d-flex justify-content-between" data-target="Diproses">
                            <a href="javascript:void(0);" class="d-flex flex-wrap align-items-center">
                                <i class="ti ti-clock ti-xs"></i>
                                <span class="align-middle ms-2">Diproses</span>
                            </a>
                            <div class="badge bg-label-warning rounded-pill badge-center">
                                {{ $cases->where('status_pengaduan', 'Diproses')->count() }}
                            </div>
                        </li>
                        <li class="d-flex justify-content-between" data-target="Investigasi">
                            <a href="javascript:void(0);" class="d-flex flex-wrap align-items-center">
                                <i class="ti ti-search ti-xs"></i>
                                <span class="align-middle ms-2">Investigasi</span>
                            </a>
                            <div class="badge bg-label-info rounded-pill badge-center">
                                {{ $cases->where('status_pengaduan', 'Investigasi')->count() }}
                            </div>
                        </li>
                        <li class="d-flex justify-content-between" data-target="Selesai">
                            <a href="javascript:void(0);" class="d-flex flex-wrap align-items-center">
                                <i class="ti ti-circle-check ti-xs"></i>
                                <span class="align-middle ms-2">Selesai</span>
                            </a>
                            <div class="badge bg-label-success rounded-pill badge-center">
                                {{ $cases->where('status_pengaduan', 'Selesai')->count() }}
                            </div>
                        </li>
                    </ul>
                    <!-- Email Filters: Category Labels -->
                    <div class="email-filter-labels">
                        <small class="fw-normal text-uppercase text-muted m-4">Kategori Kasus</small>
                        <ul class="list-unstyled mb-0 mt-2">
                            @php
                                $categories = $cases->pluck('kategori.nama_kategori')->unique()->filter();
                                $labelColors = ['bg-primary', 'bg-success', 'bg-danger', 'bg-warning', 'bg-info'];
                            @endphp
                            @forelse($categories as $index => $catName)
                                <li data-target="cat-{{ Str::slug($catName) }}">
                                    <a href="javascript:void(0);">
                                        <span
                                            class="badge badge-dot {{ $labelColors[$index % count($labelColors)] }}"></span>
                                        <span class="align-middle ms-2">{{ $catName }}</span>
                                    </a>
                                </li>
                            @empty
                                <li class="px-4 py-1 text-muted small">Belum ada kategori</li>
                            @endforelse
                        </ul>
                    </div>
                    <!--/ Email Filters -->
                </div>
            </div>
            <!--/ Email Sidebar -->

            <!-- Emails List -->
            <div class="col app-emails-list">
                <div class="shadow-none border-0">
                    <div class="emails-list-header p-3 py-lg-3 py-2">
                        <!-- Email List: Search -->
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center w-100">
                                <i class="ti ti-menu-2 ti-sm cursor-pointer d-block d-lg-none me-3" data-bs-toggle="sidebar"
                                    data-target="#app-email-sidebar" data-overlay></i>
                                <div class="mb-0 mb-lg-2 w-100">
                                    <div class="input-group input-group-merge shadow-none">
                                        <span class="input-group-text border-0 ps-0" id="email-search">
                                            <i class="ti ti-search"></i>
                                        </span>
                                        <input type="text" class="form-control email-search-input border-0"
                                            placeholder="Cari kode pengaduan atau kronologi..." aria-label="Search mail"
                                            aria-describedby="email-search">
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mb-0 mb-md-2">
                                <i class="ti ti-rotate-clockwise rotate-180 scaleX-n1-rtl cursor-pointer email-refresh me-2 mt-1"
                                    title="Refresh"></i>
                            </div>
                        </div>
                        <hr class="mx-n3 emails-list-header-hr">
                        <!-- Email List: Actions -->
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="form-check mb-0 me-2">
                                    <input class="form-check-input" type="checkbox" id="email-select-all">
                                    <label class="form-check-label" for="email-select-all"></label>
                                </div>
                                <span class="fw-semibold text-muted small ms-2">Daftar Antrean Kasus Satgas</span>
                            </div>
                            <div
                                class="email-pagination d-sm-flex d-none align-items-center flex-wrap justify-content-between justify-sm-content-end">
                                <span class="d-sm-block d-none mx-3 text-muted">1-{{ $cases->count() }} dari
                                    {{ $cases->count() }}</span>
                            </div>
                        </div>
                    </div>
                    <hr class="container-m-nx m-0">
                    <!-- Email List: Items -->
                    <div class="email-list pt-0">
                        <ul class="list-unstyled m-0" id="caseItemsList">
                            @forelse($cases as $item)
                                @php
                                    $catSlug = Str::slug($item->kategori->nama_kategori ?? 'umum');
                                    $badgeDot = match ($item->status_pengaduan) {
                                        'Baru' => 'bg-danger',
                                        'Diproses' => 'bg-warning',
                                        'Investigasi' => 'bg-info',
                                        'Selesai' => 'bg-success',
                                        default => 'bg-secondary',
                                    };
                                @endphp
                                <li class="email-list-item {{ $activeCase && $activeCase->id == $item->id ? 'email-marked-read' : '' }}"
                                    data-starred="true" data-{{ strtolower($item->status_pengaduan) }}="true"
                                    data-cat-{{ $catSlug }}="true" data-bs-toggle="sidebar"
                                    data-target="#app-email-view" data-case-id="{{ $item->id }}"
                                    onclick="loadCaseDetail({{ $item->id }})">
                                    <div class="d-flex align-items-center">
                                        <div class="form-check mb-0">
                                            <input class="email-list-item-input form-check-input" type="checkbox"
                                                id="email-{{ $item->id }}">
                                            <label class="form-check-label" for="email-{{ $item->id }}"></label>
                                        </div>
                                        <i
                                            class="email-list-item-bookmark ti ti-star ti-xs d-sm-inline-block d-none cursor-pointer ms-2 me-3"></i>

                                        <div class="avatar avatar-sm d-block flex-shrink-0 me-sm-3 me-2">
                                            <span class="avatar-initial rounded-circle bg-label-primary">
                                                {{ strtoupper(substr($item->pelapor->nama_lengkap ?? 'P', 0, 2)) }}
                                            </span>
                                        </div>

                                        <div class="email-list-item-content ms-2 ms-sm-0 me-2">
                                            <span class="h6 email-list-item-username me-2">
                                                #{{ $item->kode_pengaduan }} -
                                                {{ $item->pelapor->nama_lengkap ?? 'Pelapor Anonim' }}
                                            </span>
                                            <span
                                                class="email-list-item-subject d-xl-inline-block d-block text-muted email-list-item-kronologi">
                                                {{ $item->kronologi }}
                                            </span>
                                        </div>

                                        <div class="email-list-item-meta ms-auto d-flex align-items-center">
                                            <span class="badge badge-dot {{ $badgeDot }} me-2"
                                                title="Status: {{ $item->status_pengaduan }}"></span>
                                            <small
                                                class="email-list-item-time text-muted">{{ $item->created_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                </li>
                            @empty
                                <li class="p-4 text-center text-muted">
                                    <i class="ti ti-folder-off fs-2 mb-2 d-block"></i>
                                    <span>Belum ada pengaduan yang ditugaskan.</span>
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>
                <div class="app-overlay"></div>
            </div>
            <!-- /Emails List -->

            <!-- Email View -->
            <div class="col app-email-view flex-grow-0 bg-body" id="app-email-view">
                @if ($activeCase)
                    <div class="card shadow-none border-0 rounded-0 app-email-view-header p-3 py-md-3 py-2">
                        <!-- Email View : Title bar-->
                        <div class="d-flex justify-content-between align-items-center py-2">
                            <div class="d-flex align-items-center overflow-hidden">
                                <i class="ti ti-chevron-left ti-sm cursor-pointer me-2" data-bs-toggle="sidebar"
                                    data-target="#app-email-view"></i>
                                <h6 class="text-truncate mb-0 me-2" id="viewCaseTitle">#{{ $activeCase->kode_pengaduan }}
                                    - {{ $activeCase->kategori->nama_kategori ?? 'Kasus' }}</h6>
                                @php
                                    $viewStatusBadge = match ($activeCase->status_pengaduan) {
                                        'Baru' => 'bg-label-danger',
                                        'Diproses' => 'bg-label-warning',
                                        'Investigasi' => 'bg-label-info',
                                        'Selesai' => 'bg-label-success',
                                        default => 'bg-label-secondary',
                                    };
                                @endphp
                                <span class="badge {{ $viewStatusBadge }} rounded-pill"
                                    id="viewCaseStatusHeader">{{ $activeCase->status_pengaduan }}</span>
                            </div>
                            <!-- Email View : Action bar-->
                            <div class="d-flex align-items-center">
                                <i class="ti ti-printer mt-1 cursor-pointer d-sm-block d-none" onclick="window.print()"
                                    title="Cetak Laporan"></i>
                            </div>
                        </div>
                        <hr class="app-email-view-hr mx-n3 mb-2">
                    </div>

                    <hr class="m-0">

                    <!-- Email View : Content-->
                    <div class="app-email-view-content py-4" id="viewCaseContent">

                        <!-- Main Case Detail Card -->
                        <div class="card email-card-last mx-sm-4 mx-3 mb-4 shadow-sm border">
                            <div
                                class="card-header d-flex justify-content-between align-items-center flex-wrap bg-light-subtle py-3">
                                <div class="d-flex align-items-center mb-sm-0 mb-2">
                                    <div class="avatar avatar-md me-3">
                                        <span class="avatar-initial rounded-circle bg-primary text-white">
                                            {{ strtoupper(substr($activeCase->pelapor->nama_lengkap ?? 'P', 0, 2)) }}
                                        </span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="m-0 fw-bold" id="viewPelaporNama">
                                            {{ $activeCase->pelapor->nama_lengkap ?? 'Pelapor Anonim' }}</h6>
                                        <small class="text-muted" id="viewPelaporNpm">NPM/NIP:
                                            {{ $activeCase->pelapor->npm_nip ?? '-' }}</small>
                                        @if (isset($activeCase->pelapor->kontak_wa))
                                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $activeCase->pelapor->kontak_wa) }}"
                                                target="_blank" class="ms-2 badge bg-label-success">
                                                <i
                                                    class="ti ti-brand-whatsapp me-1"></i>{{ $activeCase->pelapor->kontak_wa }}
                                            </a>
                                        @endif
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <p class="mb-0 me-3 text-muted small" id="viewCreatedTime">
                                        <i
                                            class="ti ti-calendar me-1"></i>{{ $activeCase->created_at->translatedFormat('d F Y, H:i') }}
                                    </p>
                                </div>
                            </div>
                            <div class="card-body pt-3">
                                <!-- Details Grid -->
                                <div class="row g-3 mb-3 p-3 bg-light rounded border-start border-3 border-primary">
                                    <div class="col-md-6">
                                        <small class="text-uppercase text-muted fw-semibold d-block">Korban:</small>
                                        <span class="fw-bold text-dark"
                                            id="viewKorbanNama">{{ $activeCase->korban->nama_lengkap ?? 'Sama dengan pelapor' }}</span>
                                        @if (isset($activeCase->korban->program_studi))
                                            <small class="text-muted">({{ $activeCase->korban->program_studi }})</small>
                                        @endif
                                    </div>
                                    <div class="col-md-6">
                                        <small class="text-uppercase text-muted fw-semibold d-block">Terlapor:</small>
                                        <span class="fw-bold text-danger"
                                            id="viewTerlaporNama">{{ $activeCase->terlapor->nama_lengkap ?? 'Dalam Identifikasi' }}</span>
                                    </div>
                                    <div class="col-12">
                                        <small class="text-uppercase text-muted fw-semibold d-block">Waktu & Lokasi
                                            Kejadian:</small>
                                        <span class="text-dark" id="viewKejadianMeta">
                                            <i
                                                class="ti ti-clock me-1"></i>{{ $activeCase->tanggal_kejadian ? $activeCase->tanggal_kejadian->format('d/m/Y') : '-' }}
                                            {{ $activeCase->waktu_kejadian ?? '' }}
                                            | <i
                                                class="ti ti-map-pin me-1"></i>{{ $activeCase->lokasi_kejadian ?? 'Lokasi tidak disebutkan' }}
                                        </span>
                                    </div>
                                </div>

                                <p class="fw-bold mb-2"><i class="ti ti-align-left me-1 text-primary"></i>Uraian Kronologi
                                    Kasus:</p>
                                <div class="p-3 bg-white border rounded mb-3 text-dark small"
                                    style="white-space: pre-line;" id="viewKronologiText">
                                    {{ $activeCase->kronologi }}
                                </div>

                                <hr>
                                <p class="email-attachment-title mb-2 fw-semibold"><i
                                        class="ti ti-paperclip me-1"></i>Lampiran Bukti:</p>
                                <div class="d-flex flex-wrap gap-2" id="viewLampiranList">
                                    @forelse($activeCase->lampiran_bukti as $bukti)
                                        <a href="{{ asset('storage/' . $bukti->nama_file) }}" target="_blank"
                                            class="cursor-pointer badge bg-label-primary p-2">
                                            <i class="ti ti-file me-1"></i>{{ $bukti->nama_file }}
                                            ({{ strtoupper($bukti->tipe_file ?? 'FILE') }})
                                        </a>
                                    @empty
                                        <span class="text-muted small italic">Tidak ada file lampiran bukti yang
                                            diunggah.</span>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <!-- Log Penanganan Item Activity Timeline -->
                        <div id="viewLogsWrapper">
                            <div class="accordion mx-sm-4 mx-3 mb-3" id="caseLogsAccordion">
                                <div class="accordion-item border shadow-sm">
                                    <h2 class="accordion-header" id="caseLogsHeading">
                                        <button class="accordion-button collapsed fw-semibold py-3" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#caseLogsCollapse"
                                            aria-expanded="false" aria-controls="caseLogsCollapse">
                                            <i class="ti ti-history me-2 text-primary"></i>
                                            Riwayat Catatan Penanganan
                                            <span
                                                class="badge bg-label-primary rounded-pill ms-2">{{ $activeCase->logPenanganan->count() }}</span>
                                        </button>
                                    </h2>
                                    <div id="caseLogsCollapse" class="accordion-collapse collapse"
                                        aria-labelledby="caseLogsHeading" data-bs-parent="#caseLogsAccordion">
                                        <div class="accordion-body p-3">
                                            <ul class="timeline mb-0" id="timelineList">
                                                @forelse($activeCase->logPenanganan as $log)
                                                    <li class="timeline-item timeline-item-transparent"
                                                        style="padding-bottom: 0.6rem;">
                                                        <span
                                                            class="timeline-point {{ $log->is_public ? 'timeline-point-success' : 'timeline-point-primary' }}"
                                                            style="top: 0.2rem;"></span>
                                                        <div class="timeline-event pb-0">
                                                            <div class="timeline-header d-flex justify-content-between align-items-center flex-wrap"
                                                                style="margin-bottom: 0.15rem;">
                                                                <div class="d-flex align-items-center gap-1 mb-1 mb-sm-0">
                                                                    <strong class="text-dark"
                                                                        style="font-size: 0.72rem;">{{ $log->user->name ?? 'Petugas Satgas' }}</strong>
                                                                    @if ($log->is_public)
                                                                        <span class="badge bg-label-success"
                                                                            style="font-size: 0.54rem; padding: 0.2em 0.45em;">
                                                                            <i class="ti ti-world me-1"></i>Publik
                                                                        </span>
                                                                    @else
                                                                        <span class="badge bg-label-secondary"
                                                                            style="font-size: 0.54rem; padding: 0.2em 0.45em;">
                                                                            <i class="ti ti-lock me-1"></i>Internal
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                                <small class="text-muted" style="font-size: 0.6rem;">
                                                                    <i
                                                                        class="ti ti-clock me-1"></i>{{ $log->created_at->translatedFormat('d M Y, H:i') }}
                                                                </small>
                                                            </div>
                                                            <p class="mb-0 text-dark"
                                                                style="white-space: pre-line; font-size: 0.72rem; line-height: 1.35;">
                                                                {{ $log->catatan }}</p>
                                                        </div>
                                                    </li>
                                                @empty
                                                    <li class="text-center text-muted py-3">
                                                        <i class="ti ti-messages fs-4 mb-2 d-block"></i>
                                                        <span class="small">Belum ada catatan penanganan
                                                            sebelumnya.</span>
                                                    </li>
                                                @endforelse
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Email View : Reply / Action Form card-->
                        <div class="email-reply card mt-4 mx-sm-4 mx-3 border shadow-sm">
                            <h6 class="card-header border-0 bg-light-subtle fw-bold py-3">
                                <i class="ti ti-edit me-1 text-primary"></i>Tambah Catatan Progres & Update Status
                            </h6>
                            <div class="card-body pt-3 px-3">
                                <form id="formSubmitLog" onsubmit="handleLogSubmit(event)">
                                    @csrf
                                    <input type="hidden" name="case_id" id="replyCaseId"
                                        value="{{ $activeCase->id }}">

                                    <div class="mb-3">
                                        <textarea name="catatan" id="replyCatatan" class="form-control" rows="3"
                                            placeholder="Ketik catatan hasil investigasi, progres penanganan, atau instruksi..." required></textarea>
                                    </div>

                                    <div class="row g-3 align-items-center">
                                        <div class="col-md-6">
                                            <div class="form-check form-switch mb-0">
                                                <input class="form-check-input" type="checkbox" name="is_public"
                                                    value="1" id="replyIsPublic">
                                                <label class="form-check-label fw-semibold text-dark small"
                                                    for="replyIsPublic">
                                                    Tampilkan Catatan Ini ke Pelapor (Publik)
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-md-6 d-flex align-items-center justify-content-md-end gap-2">
                                            <div class="d-flex align-items-center gap-1">
                                                <label class="form-label mb-0 small text-muted text-nowrap"
                                                    for="replyStatusSelect">Status Kasus:</label>
                                                <select name="status_pengaduan" id="replyStatusSelect"
                                                    class="form-select form-select-sm fw-semibold">
                                                    <option value="Diproses"
                                                        {{ $activeCase->status_pengaduan == 'Diproses' ? 'selected' : '' }}>
                                                        Diproses</option>
                                                    <option value="Investigasi"
                                                        {{ $activeCase->status_pengaduan == 'Investigasi' ? 'selected' : '' }}>
                                                        Investigasi</option>
                                                    <option value="Selesai"
                                                        {{ $activeCase->status_pengaduan == 'Selesai' ? 'selected' : '' }}>
                                                        Selesai</option>
                                                </select>
                                            </div>

                                            <button type="submit" class="btn btn-primary btn-sm ms-2"
                                                id="btnSubmitReply">
                                                <i class="ti ti-send ti-xs me-1"></i>
                                                <span class="align-middle">Simpan</span>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                @else
                    <div
                        class="h-100 d-flex flex-column align-items-center justify-content-center p-5 text-center text-muted">
                        <i class="ti ti-folder-open display-3 mb-3 text-secondary"></i>
                        <h5>Pilih Laporan Kasus</h5>
                        <p class="small text-muted" style="max-width: 360px;">
                            Pilih salah satu tiket di antrean sebelah kiri untuk melihat detail kronologi dan memperbarui
                            status penanganan.
                        </p>
                    </div>
                @endif
            </div>
            <!-- Email View -->
        </div>
    </div>
@endsection

@section('page-script')
    <script src="{{ asset('assets/js/app-email.js') }}"></script>
    <script>
        const showUrlTemplate = "{{ route('satgas.my-cases.show', ':id') }}";
        const storeLogUrlTemplate = "{{ route('satgas.my-cases.store-log', ':id') }}";

        // Load case detail dynamically via AJAX
        function loadCaseDetail(caseId) {
            const url = showUrlTemplate.replace(':id', caseId);

            fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(res => {
                    if (res.status === 'success') {
                        renderCaseView(res.data);
                    }
                })
                .catch(err => console.error("Error loading case detail:", err));
        }

        // Render case view content
        function renderCaseView(data) {
            // Update Header Title & Status Badge
            const titleElem = document.getElementById('viewCaseTitle');
            if (titleElem) {
                titleElem.innerText = `#${data.kode_pengaduan} - ${data.kategori ? data.kategori.nama_kategori : 'Kasus'}`;
            }

            const statusHeader = document.getElementById('viewCaseStatusHeader');
            if (statusHeader) {
                statusHeader.innerText = data.status_pengaduan;
                statusHeader.className = `badge rounded-pill ${getStatusBadgeClass(data.status_pengaduan)}`;
            }

            // Update Reporter Info
            const pelaporNama = document.getElementById('viewPelaporNama');
            if (pelaporNama) pelaporNama.innerText = data.pelapor ? data.pelapor.nama_lengkap : 'Pelapor Anonim';

            const pelaporNpm = document.getElementById('viewPelaporNpm');
            if (pelaporNpm) pelaporNpm.innerText = `NPM/NIP: ${data.pelapor ? (data.pelapor.npm_nip || '-') : '-'}`;

            // Korban & Terlapor
            const korbanNama = document.getElementById('viewKorbanNama');
            if (korbanNama) korbanNama.innerText = data.korban ? data.korban.nama_lengkap : 'Sama dengan pelapor';

            const terlaporNama = document.getElementById('viewTerlaporNama');
            if (terlaporNama) terlaporNama.innerText = data.terlapor ? data.terlapor.nama_lengkap : 'Dalam Identifikasi';

            // Waktu & Lokasi
            const kejadianMeta = document.getElementById('viewKejadianMeta');
            if (kejadianMeta) {
                const dateStr = data.tanggal_kejadian ? data.tanggal_kejadian.split('T')[0] : '-';
                kejadianMeta.innerHTML =
                    `<i class="ti ti-clock me-1"></i>${dateStr} ${data.waktu_kejadian || ''} | <i class="ti ti-map-pin me-1"></i>${data.lokasi_kejadian || 'Lokasi tidak disebutkan'}`;
            }

            // Kronologi Text
            const kronologiText = document.getElementById('viewKronologiText');
            if (kronologiText) kronologiText.innerText = data.kronologi;

            // Lampiran Bukti
            const lampiranList = document.getElementById('viewLampiranList');
            if (lampiranList) {
                lampiranList.innerHTML = '';
                if (data.lampiran_bukti && data.lampiran_bukti.length > 0) {
                    data.lampiran_bukti.forEach((b) => {
                        lampiranList.innerHTML += `
                            <a href="/storage/${b.nama_file}" target="_blank" class="cursor-pointer badge bg-label-primary p-2">
                                <i class="ti ti-file me-1"></i>${b.nama_file} (${(b.tipe_file || 'FILE').toUpperCase()})
                            </a>
                        `;
                    });
                } else {
                    lampiranList.innerHTML =
                        '<span class="text-muted small italic">Tidak ada file lampiran bukti yang diunggah.</span>';
                }
            }

            // Timeline Logs
            renderTimelineLogs(data.log_penanganan || []);

            // Update Form hidden case ID & status dropdown
            const replyCaseId = document.getElementById('replyCaseId');
            if (replyCaseId) replyCaseId.value = data.id;

            const replyStatusSelect = document.getElementById('replyStatusSelect');
            if (replyStatusSelect) replyStatusSelect.value = data.status_pengaduan;
        }

        // Render Timeline Logs in Collapsible Accordion
        function renderTimelineLogs(logs, autoOpen = false) {
            const logsWrapper = document.getElementById('viewLogsWrapper');
            const logCountElem = document.getElementById('viewLogCount');

            if (logCountElem) logCountElem.innerText = logs.length;

            if (!logsWrapper) return;

            let timelineContent = '';
            if (logs.length === 0) {
                timelineContent = `
                    <li class="text-center text-muted py-3">
                        <i class="ti ti-messages fs-4 mb-2 d-block"></i>
                        <span class="small">Belum ada catatan penanganan sebelumnya.</span>
                    </li>
                `;
            } else {
                logs.forEach(log => {
                    const userName = log.user ? log.user.name : 'Petugas Satgas';
                    const logDate = new Date(log.created_at).toLocaleDateString('id-ID', {
                        day: 'numeric',
                        month: 'short',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });

                    const pointClass = log.is_public ? 'timeline-point-success' : 'timeline-point-primary';
                    const visibilityBadge = log.is_public ?
                        `<span class="badge bg-label-success" style="font-size: 0.54rem; padding: 0.2em 0.45em;"><i class="ti ti-world me-1"></i>Publik</span>` :
                        `<span class="badge bg-label-secondary" style="font-size: 0.54rem; padding: 0.2em 0.45em;"><i class="ti ti-lock me-1"></i>Internal</span>`;

                    timelineContent += `
                        <li class="timeline-item timeline-item-transparent" style="padding-bottom: 0.6rem;">
                            <span class="timeline-point ${pointClass}" style="top: 0.2rem;"></span>
                            <div class="timeline-event pb-0">
                                <div class="timeline-header d-flex justify-content-between align-items-center flex-wrap" style="margin-bottom: 0.15rem;">
                                    <div class="d-flex align-items-center gap-1 mb-1 mb-sm-0">
                                        <strong class="text-dark" style="font-size: 0.72rem;">${escapeHtml(userName)}</strong>
                                        ${visibilityBadge}
                                    </div>
                                    <small class="text-muted" style="font-size: 0.6rem;">
                                        <i class="ti ti-clock me-1"></i>${logDate}
                                    </small>
                                </div>
                                <p class="mb-0 text-dark" style="white-space: pre-line; font-size: 0.72rem; line-height: 1.35;">${escapeHtml(log.catatan)}</p>
                            </div>
                        </li>
                    `;
                });
            }

            const showClass = autoOpen ? 'show' : '';
            const buttonCollapsed = autoOpen ? '' : 'collapsed';
            const ariaExpanded = autoOpen ? 'true' : 'false';

            logsWrapper.innerHTML = `
                <div class="accordion mx-sm-4 mx-3 mb-3" id="caseLogsAccordion">
                    <div class="accordion-item border shadow-sm">
                        <h2 class="accordion-header" id="caseLogsHeading">
                            <button class="accordion-button ${buttonCollapsed} fw-semibold py-3" type="button"
                                data-bs-toggle="collapse" data-bs-target="#caseLogsCollapse"
                                aria-expanded="${ariaExpanded}" aria-controls="caseLogsCollapse">
                                <i class="ti ti-history me-2 text-primary"></i>
                                <span>Riwayat Catatan Penanganan</span>
                                <span class="badge bg-label-primary rounded-pill ms-2" id="viewLogCountBadge">${logs.length}</span>
                            </button>
                        </h2>
                        <div id="caseLogsCollapse" class="accordion-collapse collapse ${showClass}"
                            aria-labelledby="caseLogsHeading" data-bs-parent="#caseLogsAccordion">
                            <div class="accordion-body p-3">
                                <ul class="timeline mb-0" id="timelineList">
                                    ${timelineContent}
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            // Smooth animation expand if autoOpen requested
            if (autoOpen && window.bootstrap && window.bootstrap.Collapse) {
                const collapseElement = document.getElementById('caseLogsCollapse');
                if (collapseElement) {
                    const bsCollapse = bootstrap.Collapse.getOrCreateInstance(collapseElement);
                    bsCollapse.show();
                }
            }
        }

        // Handle Log Submit
        function handleLogSubmit(e) {
            e.preventDefault();
            const caseId = document.getElementById('replyCaseId').value;
            const form = document.getElementById('formSubmitLog');
            const btnSubmit = document.getElementById('btnSubmitReply');

            if (!caseId) return;

            const formData = new FormData(form);
            btnSubmit.disabled = true;
            btnSubmit.innerHTML = `<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...`;

            const url = storeLogUrlTemplate.replace(':id', caseId);

            fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(res => res.json())
                .then(res => {
                    btnSubmit.disabled = false;
                    btnSubmit.innerHTML =
                        `<i class="ti ti-send ti-xs me-1"></i><span class="align-middle">Simpan</span>`;

                    if (res.status === 'success') {
                        document.getElementById('replyCatatan').value = '';
                        renderCaseView(res.data);
                        // Auto-open accordion with smooth animation after log submit
                        renderTimelineLogs(res.data.log_penanganan || [], true);

                        // Update status dot in email list
                        const listItem = document.querySelector(`.email-list-item[data-case-id="${caseId}"]`);
                        if (listItem) {
                            const statusDot = listItem.querySelector('.badge-dot');
                            if (statusDot) {
                                statusDot.className =
                                    `badge badge-dot ${getStatusDotClass(res.data.status_pengaduan)} me-2`;
                            }
                        }
                    } else {
                        alert(res.message || 'Gagal menyimpan catatan penanganan.');
                    }
                })
                .catch(err => {
                    btnSubmit.disabled = false;
                    btnSubmit.innerHTML =
                        `<i class="ti ti-send ti-xs me-1"></i><span class="align-middle">Simpan</span>`;
                    console.error("Submit error:", err);
                    alert('Terjadi kesalahan saat menyimpan catatan.');
                });
        }

        // Helpers
        function getStatusBadgeClass(status) {
            switch (status) {
                case 'Baru':
                    return 'bg-label-danger';
                case 'Diproses':
                    return 'bg-label-warning';
                case 'Investigasi':
                    return 'bg-label-info';
                case 'Selesai':
                    return 'bg-label-success';
                default:
                    return 'bg-label-secondary';
            }
        }

        function getStatusDotClass(status) {
            switch (status) {
                case 'Baru':
                    return 'bg-danger';
                case 'Diproses':
                    return 'bg-warning';
                case 'Investigasi':
                    return 'bg-info';
                case 'Selesai':
                    return 'bg-success';
                default:
                    return 'bg-secondary';
            }
        }

        function escapeHtml(str) {
            if (!str) return '';
            return str.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(
                /'/g, "&#039;");
        }
    </script>
@endsection
