@extends('layouts/layoutMaster')

@section('title', 'Kategori Kasus')

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
    <script src="{{ asset('form-data-json.min.js') }}"></script>
    <script src="{{ asset('admin/category-pengaduan.js') }}"></script>
@endsection

@section('content')
    <h3>
        Kategori Kasus
        <small class="text-muted fw-light">Kelola data kategori kasus perundungan</small>
    </h3>

    <div class="row">
        <div class="col">
            <div class="card mt-3">
                <div class="card-datatable table-responsive text-nowrap">
                    <table class="dt-table-category table">
                        <thead class="border-top table-light">
                            <tr>
                                <th>NO</th>
                                <th>NAMA KATEGORI</th>
                                <th>STATUS</th>
                                <th>DIBUAT</th>
                                <th>AKSI</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @include('_partials.admin.category-modal')
@endsection
