@extends('layouts/layoutMaster')

@section('title', 'FAQ (Pertanyaan Umum)')

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
    <script src="{{ asset('admin/faq.js') }}"></script>
@endsection

@section('content')
    <h3>
        Kelola FAQ
        <small class="text-muted fw-light">Halaman pengelolaan Pertanyaan yang Sering Diajukan</small>
    </h3>

    <div class="row">
        <div class="col">
            <div class="card mt-3">
                <div class="card-datatable table-responsive text-nowrap">
                    <table class="dt-table-faq table">
                        <thead class="border-top table-light">
                            <tr>
                                <th>NO</th>
                                <th>PERTANYAAN</th>
                                <th>JAWABAN</th>
                                <th>URUTAN</th>
                                <th>STATUS</th>
                                <th>AKSI</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @include('_partials.admin.faq-modal')
@endsection
