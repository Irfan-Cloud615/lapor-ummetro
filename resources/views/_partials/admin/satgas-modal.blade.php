<!-- Modal Satgas -->
<div class="modal fade" id="modalSatgas" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalSatgasTitle">Tambah Satgas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form-satgas">
                @csrf
                <input type="hidden" id="satgas-id">
                <div class="modal-body">
                    <!-- Nama -->
                    <div class="mb-3">
                        <label for="satgas-name" class="form-label">Nama <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="satgas-name" name="name"
                            placeholder="Masukkan nama satgas">
                        <div class="invalid-feedback" id="error-name"></div>
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="satgas-email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="satgas-email" name="email"
                            placeholder="Masukkan email satgas">
                        <div class="invalid-feedback" id="error-email"></div>
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label for="satgas-password" class="form-label">
                            Password <span class="text-danger" id="password-required">*</span>
                            <small class="text-muted" id="password-hint" style="display:none;">(Kosongkan jika tidak
                                ingin mengubah password)</small>
                        </label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="satgas-password" name="password"
                                placeholder="Masukkan password">
                            <span class="input-group-text cursor-pointer" id="toggle-password">
                                <i class="ti ti-eye" id="password-eye-icon"></i>
                            </span>
                        </div>
                        <div class="invalid-feedback d-block" id="error-password"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btn-simpan-satgas">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
