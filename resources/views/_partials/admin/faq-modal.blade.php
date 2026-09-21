{{-- Modal Tambah / Edit FAQ --}}
<div class="modal fade" id="modalFaq" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalFaqTitle">Tambah FAQ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form-faq">
                @csrf
                <input type="hidden" id="faq-id" value="">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="faq-pertanyaan" class="form-label">Pertanyaan <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="faq-pertanyaan" name="pertanyaan"
                            placeholder="Masukkan pertanyaan FAQ" required>
                        <div class="invalid-feedback" id="error-pertanyaan"></div>
                    </div>
                    <div class="mb-3">
                        <label for="faq-jawaban" class="form-label">Jawaban <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="faq-jawaban" name="jawaban" rows="4" placeholder="Masukkan jawaban FAQ"
                            required></textarea>
                        <div class="invalid-feedback" id="error-jawaban"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="faq-urutan" class="form-label">Urutan Tampil</label>
                            <input type="number" class="form-control" id="faq-urutan" name="urutan" placeholder="0"
                                value="0" min="0">
                            <small class="text-muted">Urutan posisi tampil di halaman FAQ (semakin kecil semakin
                                atas)</small>
                            <div class="invalid-feedback" id="error-urutan"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="faq-is-active" class="form-label">Status <span
                                    class="text-danger">*</span></label>
                            <select class="form-select" id="faq-is-active" name="is_active" required>
                                <option value="1">Aktif</option>
                                <option value="0">Tidak Aktif</option>
                            </select>
                        </div>
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
