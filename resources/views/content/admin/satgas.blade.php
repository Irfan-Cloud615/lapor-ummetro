@extends('layouts/layoutMaster')

@section('title', 'Manajemen Satgas')

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
    <script src="{{ asset('admin/satgas.js') }}"></script>
@endsection

@section('content')
    <h3>
        Manajemen Satgas
        <small class="text-muted fw-light">Kelola akun anggota satgas pengaduan</small>
    </h3>

    <div class="row">
        <div class="col">
            <div class="card mt-3">
                <div class="card-datatable table-responsive text-nowrap">
                    <table class="dt-table-satgas table">
                        <thead class="border-top table-light">
                            <tr>
                                <th>NO</th>
                                <th>NAMA</th>
                                <th>EMAIL</th>
                                <th>TANGGAL DAFTAR</th>
                                <th>AKSI</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @include('_partials.admin.satgas-modal')
@endsection
