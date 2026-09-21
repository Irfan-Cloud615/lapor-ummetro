@extends('layouts.layoutMaster')

@section('title', 'Laporan Masuk')

@section('page-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/animate-css/animate.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
    <style>
        /* ── Layout Kanban ──────────────────────────────────────────────── */
        .kanban-outer-grid {
            display: grid;
            grid-template-columns: 320px 1fr;
            gap: 1.25rem;
            align-items: start;
        }

        @media (max-width: 768px) {
            .kanban-outer-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Kolom Antrean Masuk */
        .kanban-col-header {
            font-size: .75rem;
            font-weight: 700;
            letter-spacing: .07em;
            text-transform: uppercase;
            padding: .65rem 1rem;
            border-radius: .5rem .5rem 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .kanban-drop-zone {
            min-height: 140px;
            border-radius: 0 0 .5rem .5rem;
            padding: .75rem;
            transition: background .2s, outline .2s;
        }

        .kanban-drop-zone.drag-over {
            outline: 2px dashed var(--bs-primary);
            background: rgba(var(--bs-primary-rgb), .07) !important;
        }

        /* Warna kolom antrean */
        .col-unassigned .kanban-col-header {
            background: rgba(108, 117, 125, .12);
            color: #6c757d;
        }

        .col-unassigned .kanban-drop-zone {
            background: rgba(108, 117, 125, .05);
        }

        /* Kartu pengaduan */
        .kanban-ticket-card {
            cursor: grab;
            transition: box-shadow .2s, transform .18s;
            user-select: none;
            margin-bottom: .75rem;
        }

        .kanban-ticket-card:hover {
            box-shadow: 0 .5rem 1.5rem rgba(0, 0, 0, .13) !important;
            transform: translateY(-2px);
        }

        .kanban-ticket-card:active {
            cursor: grabbing;
        }

        .kanban-ticket-card.dragging {
            opacity: .45;
            transform: rotate(2deg);
        }

        /* ── Grid Satgas 2-kolom ──────────────────────────────────────────── */
        .staff-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.25rem;
            align-items: start;
        }

        @media (max-width: 576px) {
            .staff-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Kolom setiap satgas */
        .staff-col {
            border-radius: .5rem;
            overflow: hidden;
        }

        .staff-col-header {
            font-size: .75rem;
            font-weight: 700;
            letter-spacing: .07em;
            text-transform: uppercase;
            padding: .65rem 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(var(--bs-primary-rgb), .1);
            color: var(--bs-primary);
        }

        .staff-col-body {
            background: rgba(var(--bs-primary-rgb), .04);
            min-height: 120px;
            padding: .75rem;
            border-radius: 0 0 .5rem .5rem;
            transition: background .2s, outline .2s;
        }

        .staff-col-body.drag-over {
            outline: 2px dashed var(--bs-success);
            background: rgba(var(--bs-success-rgb), .07) !important;
        }

        /* ── Kartu Stack dalam kolom satgas ──────────────────────── */
        .ticket-stack-wrapper {
            position: relative;
            margin-bottom: .5rem;
        }

        .ticket-stack {
            position: relative;
            overflow: hidden;
            border-radius: .5rem;
        }

        .stack-inner {
            display: flex;
            transition: transform .3s ease;
        }

        .stack-inner .kanban-ticket-card {
            flex: 0 0 100%;
            margin-bottom: 0;
            min-height: 130px;
            cursor: grab;
        }

        /* Tombol navigasi stack */
        .stack-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: .35rem;
            padding: 0 .25rem;
        }

        .stack-nav .stack-btn {
            background: none;
            border: 1px solid var(--bs-border-color);
            border-radius: .375rem;
            padding: 0 .4rem;
            font-size: .75rem;
            line-height: 1.6;
            cursor: pointer;
            color: var(--bs-secondary);
            transition: background .15s, color .15s;
        }

        .stack-nav .stack-btn:hover {
            background: var(--bs-primary);
            border-color: var(--bs-primary);
            color: #fff;
        }

        .stack-nav .stack-counter {
            font-size: .72rem;
            color: var(--bs-muted);
        }

        /* Placeholder kosong */
        .zone-empty {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #adb5bd;
            font-size: .8rem;
            height: 100px;
        }
    </style>
@endsection

@section('page-script')
    <script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
    <script>
        const ASSIGN_BASE_URL = "{{ route('admin.laporan-masuk.assign', ':id') }}";
        const DETAIL_BASE_URL = "{{ route('admin.laporan-masuk.show', ':id') }}";
        const STATUS_BASE_URL = "{{ route('admin.laporan-masuk.status', ':id') }}";
        const DELETE_BASE_URL = "{{ route('admin.laporan-masuk.destroy', ':id') }}";
        const CSRF_TOKEN = "{{ csrf_token() }}";
    </script>
    <script src="{{ asset('admin/laporan-masuk.js') }}"></script>
@endsection

@section('content')

    <div class="d-flex align-items-center mb-4 py-2">
        <div>
            <h4 class="mb-0"><span class="text-muted fw-light">Admin /</span> Laporan Masuk</h4>
            <small class="text-muted">Seret kartu pengaduan ke kolom Satgas untuk mendistribusikan penanganan kasus.</small>
        </div>
    </div>

    <div class="kanban-outer-grid">

        {{-- ╔════════════════════════════════════╗ --}}
        {{-- ║  KOLOM 1: ANTREAN MASUK           ║ --}}
        {{-- ╚════════════════════════════════════╝ --}}
        <div class="col-unassigned">
            <div class="kanban-col-header">
                <span><i class="ti ti-inbox me-1"></i> Antrean Masuk</span>
                <span class="badge bg-label-secondary" id="badge-unassigned">{{ $unassignedPengaduans->count() }}</span>
            </div>
            <div class="kanban-drop-zone" id="zone-unassigned" data-column-type="unassigned">

                @if ($unassignedPengaduans->isEmpty())
                    <div class="zone-empty">
                        <i class="ti ti-checks fs-2 mb-1"></i>
                        <span>Semua pengaduan sudah ditugaskan!</span>
                    </div>
                @elseif($unassignedPengaduans->count() > 2)
                    <div class="ticket-stack-wrapper">
                        <div class="ticket-stack">
                            <div class="stack-inner" id="stack-unassigned" data-current="0"
                                data-total="{{ $unassignedPengaduans->count() }}">
                                @foreach ($unassignedPengaduans as $item)
                                    @php
                                        $statusMap = [
                                            'Baru' => ['label' => 'Baru', 'cls' => 'bg-label-info'],
                                            'Diproses' => ['label' => 'Diproses', 'cls' => 'bg-label-primary'],
                                            'Investigasi' => ['label' => 'Investigasi', 'cls' => 'bg-label-warning'],
                                            'Selesai' => ['label' => 'Selesai', 'cls' => 'bg-label-success'],
                                            'Ditolak' => ['label' => 'Ditolak', 'cls' => 'bg-label-danger'],
                                        ];
                                        $st = $statusMap[$item->status_pengaduan] ?? [
                                            'label' => $item->status_pengaduan,
                                            'cls' => 'bg-label-secondary',
                                        ];
                                    @endphp
                                    <div class="card kanban-ticket-card shadow-sm border-0" draggable="true"
                                        data-ticket-id="{{ $item->id }}"
                                        data-ticket-number="{{ $item->kode_pengaduan }}"
                                        data-title="Pengaduan #{{ $item->kode_pengaduan }}"
                                        data-status="{{ $item->status_pengaduan }}"
                                        data-status-label="{{ $st['label'] }}" data-status-cls="{{ $st['cls'] }}"
                                        data-category="{{ $item->kategori->nama_kategori ?? '-' }}"
                                        data-reporter="{{ $item->pelapor->nama_lengkap ?? '-' }}"
                                        data-description="{{ Str::limit($item->kronologi, 200) }}"
                                        data-created="{{ $item->created_at->diffForHumans() }}">
                                        <div class="card-body pb-3">
                                            <div class="d-flex justify-content-between align-items-start mb-1">
                                                <span
                                                    class="text-muted small fw-semibold">{{ $item->kode_pengaduan }}</span>
                                                <span class="badge {{ $st['cls'] }}">{{ $st['label'] }}</span>
                                            </div>
                                            <h6 class="card-title mb-1 text-truncate"
                                                title="Pelapor: {{ $item->pelapor->nama_lengkap ?? '-' }}">
                                                Pelapor: {{ $item->pelapor->nama_lengkap ?? '-' }}
                                            </h6>
                                            <p class="card-text text-muted small text-truncate mb-2">{{ $item->kronologi }}
                                            </p>
                                            <div class="d-flex align-items-center gap-2 text-muted small">
                                                <i class="ti ti-clock ti-xs"></i>
                                                <span>{{ $item->created_at->diffForHumans() }}</span>
                                                <span
                                                    class="ms-auto badge bg-label-secondary">{{ $item->kategori->nama_kategori ?? '-' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="stack-nav">
                            <button class="stack-btn" data-stack-id="stack-unassigned" data-dir="-1">‹ Prev</button>
                            <span class="stack-counter" id="counter-unassigned">1 /
                                {{ $unassignedPengaduans->count() }}</span>
                            <button class="stack-btn" data-stack-id="stack-unassigned" data-dir="1">Next ›</button>
                        </div>
                    </div>
                @else
                    @foreach ($unassignedPengaduans as $item)
                        @php
                            $statusMap = [
                                'Baru' => ['label' => 'Baru', 'cls' => 'bg-label-info'],
                                'Diproses' => ['label' => 'Diproses', 'cls' => 'bg-label-primary'],
                                'Investigasi' => ['label' => 'Investigasi', 'cls' => 'bg-label-warning'],
                                'Selesai' => ['label' => 'Selesai', 'cls' => 'bg-label-success'],
                                'Ditolak' => ['label' => 'Ditolak', 'cls' => 'bg-label-danger'],
                            ];
                            $st = $statusMap[$item->status_pengaduan] ?? [
                                'label' => $item->status_pengaduan,
                                'cls' => 'bg-label-secondary',
                            ];
                        @endphp
                        <div class="card kanban-ticket-card shadow-sm border-0" draggable="true"
                            data-ticket-id="{{ $item->id }}" data-ticket-number="{{ $item->kode_pengaduan }}"
                            data-title="Pengaduan #{{ $item->kode_pengaduan }}"
                            data-status="{{ $item->status_pengaduan }}" data-status-label="{{ $st['label'] }}"
                            data-status-cls="{{ $st['cls'] }}"
                            data-category="{{ $item->kategori->nama_kategori ?? '-' }}"
                            data-reporter="{{ $item->pelapor->nama_lengkap ?? '-' }}"
                            data-description="{{ Str::limit($item->kronologi, 200) }}"
                            data-created="{{ $item->created_at->diffForHumans() }}">
                            <div class="card-body pb-3">
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <span class="text-muted small fw-semibold">{{ $item->kode_pengaduan }}</span>
                                    <span class="badge {{ $st['cls'] }}">{{ $st['label'] }}</span>
                                </div>
                                <h6 class="card-title mb-1 text-truncate"
                                    title="Pelapor: {{ $item->pelapor->nama_lengkap ?? '-' }}">
                                    Pelapor: {{ $item->pelapor->nama_lengkap ?? '-' }}
                                </h6>
                                <p class="card-text text-muted small text-truncate mb-2">{{ $item->kronologi }}</p>
                                <div class="d-flex align-items-center gap-2 text-muted small">
                                    <i class="ti ti-clock ti-xs"></i>
                                    <span>{{ $item->created_at->diffForHumans() }}</span>
                                    <span
                                        class="ms-auto badge bg-label-secondary">{{ $item->kategori->nama_kategori ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif

            </div>
        </div>

        {{-- ╔════════════════════════════════════╗ --}}
        {{-- ║  KOLOM 2+: GRID SATGAS             ║ --}}
        {{-- ╚════════════════════════════════════╝ --}}
        <div class="staff-grid">

            @forelse($satgasUsers as $satgas)
                @php
                    $satgasPengaduans = $assignedPengaduans->get($satgas->id, collect());
                    $initial = strtoupper(substr($satgas->name, 0, 1));
                    $colors = ['bg-primary', 'bg-success', 'bg-info', 'bg-warning', 'bg-danger', 'bg-secondary'];
                    $color = $colors[$satgas->id % count($colors)];
                @endphp
                <div class="staff-col" data-column-type="assigned" data-staff-id="{{ $satgas->id }}"
                    data-staff-name="{{ $satgas->name }}">
                    <div class="staff-col-header">
                        <span>
                            <span class="avatar avatar-xs d-inline-flex me-1">
                                <span class="avatar-initial rounded-circle {{ $color }} text-white"
                                    style="font-size:.65rem">{{ $initial }}</span>
                            </span>
                            {{ $satgas->name }}
                        </span>
                        <span class="badge bg-label-primary staff-count">{{ $satgasPengaduans->count() }}</span>
                    </div>
                    <div class="staff-col-body kanban-drop-zone" id="zone-staff-{{ $satgas->id }}">

                        @if ($satgasPengaduans->isEmpty())
                            <div class="zone-empty">
                                <i class="ti ti-drag-drop fs-2 mb-1"></i>
                                <span>Seret pengaduan ke sini</span>
                            </div>
                        @else
                            {{-- Stack Slider --}}
                            <div class="ticket-stack-wrapper">
                                <div class="ticket-stack">
                                    <div class="stack-inner" id="stack-{{ $satgas->id }}" data-current="0"
                                        data-total="{{ $satgasPengaduans->count() }}">
                                        @foreach ($satgasPengaduans as $item)
                                            @php
                                                $statusMap2 = [
                                                    'Baru' => ['label' => 'Baru', 'cls' => 'bg-label-info'],
                                                    'Diproses' => ['label' => 'Diproses', 'cls' => 'bg-label-primary'],
                                                    'Investigasi' => [
                                                        'label' => 'Investigasi',
                                                        'cls' => 'bg-label-warning',
                                                    ],
                                                    'Selesai' => ['label' => 'Selesai', 'cls' => 'bg-label-success'],
                                                    'Ditutup' => ['label' => 'Ditutup', 'cls' => 'bg-label-secondary'],
                                                    'Ditolak' => ['label' => 'Ditolak', 'cls' => 'bg-label-danger'],
                                                ];
                                                $st2 = $statusMap2[$item->status_pengaduan] ?? [
                                                    'label' => $item->status_pengaduan,
                                                    'cls' => 'bg-label-secondary',
                                                ];
                                            @endphp
                                            <div class="card kanban-ticket-card shadow-sm border border-success"
                                                draggable="true" data-ticket-id="{{ $item->id }}"
                                                data-ticket-number="{{ $item->kode_pengaduan }}"
                                                data-title="Pengaduan #{{ $item->kode_pengaduan }}"
                                                data-status="{{ $item->status_pengaduan }}"
                                                data-status-label="{{ $st2['label'] }}"
                                                data-status-cls="{{ $st2['cls'] }}"
                                                data-category="{{ $item->kategori->nama_kategori ?? '-' }}"
                                                data-reporter="{{ $item->pelapor->nama_lengkap ?? '-' }}"
                                                data-description="{{ Str::limit($item->kronologi, 200) }}"
                                                data-created="{{ $item->created_at->diffForHumans() }}"
                                                data-assigned-to="{{ $satgas->id }}">
                                                <div class="card-body pb-3">
                                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                                        <span
                                                            class="text-muted small fw-semibold">{{ $item->kode_pengaduan }}</span>
                                                        <span
                                                            class="badge {{ $st2['cls'] }}">{{ $st2['label'] }}</span>
                                                    </div>
                                                    <h6 class="card-title mb-1 text-truncate">Pelapor:
                                                        {{ $item->pelapor->nama_lengkap ?? '-' }}</h6>
                                                    <p class="card-text text-muted small text-truncate mb-2">
                                                        {{ $item->kronologi }}</p>
                                                    <div class="d-flex align-items-center gap-2 text-muted small">
                                                        <i class="ti ti-clock ti-xs"></i>
                                                        <span>{{ $item->created_at->diffForHumans() }}</span>
                                                        <span class="ms-auto badge bg-label-success"><i
                                                                class="ti ti-user-check me-1"></i>Assigned</span>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-2 mt-2 pt-2 border-top">
                                                        <i class="ti ti-clock-play text-primary ti-xs"></i>
                                                        {{-- <span class="fw-semibold text-primary timer-display small"
                                                            data-assigned-at="{{ $item->updated_at ? $item->updated_at->toIso8601String() : '' }}">Menghitung
                                                            waktu...</span> --}}
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="stack-nav">
                                    <button class="stack-btn" data-stack-id="stack-{{ $satgas->id }}" data-dir="-1">‹
                                        Prev</button>
                                    <span class="stack-counter" id="counter-{{ $satgas->id }}">1 /
                                        {{ $satgasPengaduans->count() }}</span>
                                    <button class="stack-btn" data-stack-id="stack-{{ $satgas->id }}"
                                        data-dir="1">Next
                                        ›</button>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            @empty
                <div class="col-span-2 text-center text-muted py-5">
                    <i class="ti ti-user-off fs-1 d-block mb-2"></i>
                    Belum ada anggota Satgas yang terdaftar.
                </div>
            @endforelse

        </div>
    </div>

    {{-- Modal Detail Pengaduan --}}
    @include('_partials.admin.modal-pengaduan-detail')

@endsection
