<!-- Modal Detail Pengaduan -->
<div class="modal fade" id="ticketDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom pb-3">
                <div>
                    <span class="text-muted small" id="modal-kode-pengaduan">#LPR-0000-0000</span>
                    <h5 class="modal-title mb-0 mt-1" id="modal-ticket-title">Detail Pengaduan</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <!-- Status & Badge Header -->
                    <div
                        class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-2 pb-2 border-bottom">
                        <div class="d-flex align-items-center gap-2">
                            <div class="avatar avatar-sm">
                                <span class="avatar-initial rounded-circle bg-label-primary"
                                    id="modal-reporter-initial">-</span>
                            </div>
                            <div>
                                <p class="mb-0 fw-semibold small" id="modal-reporter-name">Nama Pelapor</p>
                                <small class="text-muted" id="modal-reporter-status">Status Pelapor</small>
                            </div>
                        </div>
                        <div class="d-flex gap-2 flex-wrap align-items-center">
                            <span class="badge" id="modal-status-badge">Status: -</span>
                            <span class="badge bg-label-secondary" id="modal-category-badge">Kategori: -</span>
                        </div>
                    </div>

                    <!-- Informasi Pelapor -->
                    <div class="col-12">
                        <h6 class="fw-semibold text-primary mb-2"><i class="ti ti-user me-1"></i> Data Pelapor</h6>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small text-uppercase fw-semibold">Nama Pelapor</label>
                        <p class="mb-0 fw-semibold" id="modal-pelapor-nama">-</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small text-uppercase fw-semibold">NPM / NIP</label>
                        <p class="mb-0 fw-semibold" id="modal-pelapor-npm-nip">-</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small text-uppercase fw-semibold">Status Pelapor</label>
                        <p class="mb-0 fw-semibold" id="modal-pelapor-status-text">-</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small text-uppercase fw-semibold">Kontak WA</label>
                        <p class="mb-0 fw-semibold" id="modal-pelapor-wa">-</p>
                    </div>

                    <!-- Informasi Korban & Terlapor -->
                    <div class="col-12 pt-2 border-top">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <h6 class="fw-semibold text-danger mb-2"><i class="ti ti-heart-broken me-1"></i> Data
                                    Korban</h6>
                                <div class="bg-body p-3 rounded border">
                                    <div class="mb-2">
                                        <small class="text-muted d-block text-uppercase fw-semibold">Nama Korban</small>
                                        <span class="fw-semibold" id="modal-korban-nama">-</span>
                                    </div>
                                    <div class="mb-2">
                                        <small class="text-muted d-block text-uppercase fw-semibold">Program
                                            Studi</small>
                                        <span class="fw-semibold" id="modal-korban-prodi">-</span>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block text-uppercase fw-semibold">Usia</small>
                                        <span class="fw-semibold" id="modal-korban-usia">-</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6 class="fw-semibold text-warning mb-2"><i class="ti ti-shield-alert me-1"></i> Data
                                    Terlapor</h6>
                                <div class="bg-body p-3 rounded border">
                                    <div class="mb-2">
                                        <small class="text-muted d-block text-uppercase fw-semibold">Nama
                                            Terlapor</small>
                                        <span class="fw-semibold" id="modal-terlapor-nama">-</span>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block text-uppercase fw-semibold">Status
                                            Terlapor</small>
                                        <span class="fw-semibold" id="modal-terlapor-status">-</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Rincian Kejadian -->
                    <div class="col-12 pt-2 border-top">
                        <h6 class="fw-semibold text-primary mb-2"><i class="ti ti-calendar-event me-1"></i> Rincian
                            Kejadian</h6>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small text-uppercase fw-semibold">Tanggal &amp; Waktu
                            Kejadian</label>
                        <p class="mb-0 fw-semibold" id="modal-waktu-kejadian">-</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small text-uppercase fw-semibold">Lokasi Kejadian</label>
                        <p class="mb-0 fw-semibold" id="modal-lokasi-kejadian">-</p>
                    </div>
                    <div class="col-12">
                        <label class="form-label text-muted small text-uppercase fw-semibold">Saksi</label>
                        <p class="mb-0 fw-semibold" id="modal-saksi">-</p>
                    </div>
                    <div class="col-12">
                        <label class="form-label text-muted small text-uppercase fw-semibold">Kronologi Kejadian</label>
                        <p class="border rounded p-3 bg-body mb-0" id="modal-description">Kronologi belum tersedia.</p>
                    </div>

                    <!-- Lampiran Bukti -->
                    <div class="col-12 pt-2 border-top">
                        <label class="form-label text-muted small text-uppercase fw-semibold">Lampiran Bukti</label>
                        <div class="border rounded p-3 text-center bg-body" id="modal-attachment-area">
                            <i class="ti ti-photo-off fs-1 text-muted mb-2 d-block"></i>
                            <p class="text-muted small mb-0">Tidak ada lampiran bukti yang diunggah.</p>
                        </div>
                    </div>

                    <!-- Tim Satgas -->
                    <div class="col-md-6 pt-2 border-top">
                        <label class="form-label text-muted small text-uppercase fw-semibold">Waktu Masuk
                            Laporan</label>
                        <p class="mb-0 fw-semibold" id="modal-created-at">-</p>
                    </div>
                    <div class="col-md-6 pt-2 border-top">
                        <label class="form-label text-muted small text-uppercase fw-semibold">Satgas Penanggung
                            Jawab</label>
                        <p class="mb-0" id="modal-assigned-to">
                            <span class="badge bg-label-warning">Belum Ditugaskan</span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top pt-3">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                    <i class="ti ti-x me-1"></i> Tutup
                </button>
                <div class="btn-group" id="group-change-status">
                    <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <i class="ti ti-refresh me-1"></i> Ubah Status
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item btn-set-status" data-status="Selesai"
                                href="javascript:void(0);">Selesai</a></li>
                        <li><a class="dropdown-item btn-set-status" data-status="Ditutup"
                                href="javascript:void(0);">Ditutup</a></li>
                        <li><a class="dropdown-item btn-set-status" data-status="Ditolak"
                                href="javascript:void(0);">Ditolak</a></li>
                    </ul>
                </div>
                <button type="button" class="btn btn-danger .btn-delete" id="btn-delete-ticket">
                    <i class="ti ti-trash me-1"></i> Hapus Laporan
                </button>
            </div>
        </div>
    </div>
</div>
