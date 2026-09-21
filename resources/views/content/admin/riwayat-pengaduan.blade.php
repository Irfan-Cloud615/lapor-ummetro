@extends('layouts/layoutMaster')

@section('title', 'Riwayat Pengaduan')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/animate-css/animate.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
@endsection

@section('page-script')
    <script>
        const DETAIL_BASE_URL = "{{ route('admin.laporan-masuk.show', ':id') }}";
        const STATUS_BASE_URL = "{{ route('admin.laporan-masuk.status', ':id') }}";
        const DELETE_BASE_URL = "{{ route('admin.laporan-masuk.destroy', ':id') }}";
        const CSRF_TOKEN = "{{ csrf_token() }}";
    </script>
    <script src="{{ asset('admin/riwayat-pengaduan.js') }}"></script>
@endsection

@section('content')
    <h3>
        Riwayat Pengaduan
        <small class="text-muted fw-light">Daftar riwayat seluruh pengaduan yang telah diproses</small>
    </h3>

    <div class="row">
        <div class="col">
            <div class="card mt-3">
                <div class="card-datatable table-responsive text-nowrap">
                    <table class="dt-table-riwayat table">
                        <thead class="border-top table-light">
                            <tr>
                                <th>NO</th>
                                <th>KODE PENGADUAN</th>
                                <th>NAMA PELAPOR</th>
                                <th>KATEGORI KASUS</th>
                                <th>TANGGAL KEJADIAN</th>
                                <th>STATUS</th>
                                <th>AKSI</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @include('_partials.admin.modal-pengaduan-detail')
@endsection
