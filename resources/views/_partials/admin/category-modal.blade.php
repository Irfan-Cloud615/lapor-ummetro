{{-- Modal Tambah / Edit Kategori --}}
<div class="modal fade" id="modalKategori" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalKategoriTitle">Tambah Kategori Kasus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form-kategori">
                @csrf
                <input type="hidden" id="kategori-id" value="">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="kategori-nama" class="form-label">Nama Kategori <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="kategori-nama" name="nama_kategori"
                            placeholder="Masukkan nama kategori kasus" required>
                        <div class="invalid-feedback" id="error-nama-kategori"></div>
                    </div>
                    <div class="mb-3">
                        <label for="kategori-is-active" class="form-label">Status <span
                                class="text-danger">*</span></label>
                        <select class="form-select" id="kategori-is-active" name="is_active" required>
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btn-simpan">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
