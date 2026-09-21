/**
 * Lapor Perundungan
 * Form wizard (bs-stepper) + Select2 + toastr + submit AJAX
 */

$(function () {
  var form = $('#form-lapor');

  // Konfigurasi toastr
  toastr.options = {
    closeButton: true,
    progressBar: true,
    timeOut: 6000
  };

  // ==========================================================================
  // Select2 : kategori kasus, status pelapor, status terlapor
  // ==========================================================================
  form.find('.select2').each(function () {
    $(this).select2({
      width: '100%',
      placeholder: $(this).data('placeholder')
    });
  });

  // Hilangkan tanda merah saat pilihan diperbaiki
  form.on('change', 'select.select2-hidden-accessible', function () {
    $(this).next('.select2').find('.select2-selection').removeClass('border-danger');
  });

  // ==========================================================================
  // Data Korban : tampil & wajib hanya jika pelapor BUKAN korban
  // ==========================================================================
  var selectStatusPelapor = $('#status_pelapor');
  var blokKorban = $('#blok-korban');
  var infoKorbanDiri = $('#info-korban-diri');
  function perbaruiBlokKorban() {
    if (!selectStatusPelapor.length) return;
    var adalahKorban = selectStatusPelapor.val() === 'Korban';
    blokKorban.toggleClass('d-none', adalahKorban);
    infoKorbanDiri.toggleClass('d-none', !adalahKorban);

    // Input pada blok tersembunyi dinonaktifkan agar isinya tidak ikut terkirim
    blokKorban.find('input, select').prop('disabled', adalahKorban);
    $('#nama_korban').prop('required', !adalahKorban);
  }
  if (selectStatusPelapor.length) {
    selectStatusPelapor.on('change', perbaruiBlokKorban);
    perbaruiBlokKorban();
  }

  // ==========================================================================
  // Wizard
  // ==========================================================================
  var stepperEl = document.getElementById('wizard-lapor');
  var stepper = new Stepper(stepperEl, { linear: true });

  // Validasi satu langkah: input biasa pakai validasi native, select2 dicek manual
  function validatePane(pane) {
    var belumLengkap = [];

    pane.querySelectorAll('[required]').forEach(function (el) {
      // Lewati field pada blok tersembunyi (mis. data korban saat pelapor = korban)
      if (el.closest('.d-none')) {
        return;
      }

      // Select2 : select aslinya disembunyikan, cek nilainya manual
      if (el.tagName === 'SELECT') {
        if (!el.value) {
          belumLengkap.push(el.dataset.label || el.name);
          $(el).next('.select2').find('.select2-selection').addClass('border-danger');
        }
        return;
      }

      if (!el.checkValidity()) {
        el.reportValidity();
        belumLengkap.push(el.dataset.label || el.name);
      }
    });

    if (belumLengkap.length) {
      toastr.warning('Harap lengkapi: ' + Array.from(new Set(belumLengkap)).join(', '), 'Data belum lengkap');
    }
    return belumLengkap.length === 0;
  }

  form.on('click', '.btn-next', function (e) {
    e.preventDefault();
    var pane = $(this).closest('.bs-stepper-pane')[0];
    if (validatePane(pane)) {
      stepper.next();
    }
  });

  form.on('click', '.btn-prev', function (e) {
    e.preventDefault();
    stepper.previous();
  });

  // ==========================================================================
  // Daftar file bukti terpilih
  // ==========================================================================
  function formatUkuran(bytes) {
    if (bytes >= 1024 * 1024) {
      return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
    }
    return Math.round(bytes / 1024) + ' KB';
  }

  var inputBukti = document.getElementById('bukti');
  var daftarBukti = $('#daftar-bukti');

  $(inputBukti).on('change', function () {
    daftarBukti.empty();
    if (!this.files.length) {
      return;
    }
    if (this.files.length > 5) {
      toastr.warning('Maksimal 5 file bukti. Silakan kurangi pilihan file.', 'Terlalu banyak file');
    }

    var adaFileBesar = false;
    Array.prototype.forEach.call(this.files, function (file) {
      if (file.size > 5 * 1024 * 1024) {
        adaFileBesar = true;
      }
      var li = $('<li class="d-flex justify-content-between gap-2"></li>');
      li.append(
        $('<span class="text-truncate"></span>')
          .append('<i class="ti ti-paperclip me-1"></i>')
          .append(document.createTextNode(file.name))
      );
      li.append($('<small class="text-muted text-nowrap"></small>').text(formatUkuran(file.size)));
      daftarBukti.append(li);
    });

    if (adaFileBesar) {
      toastr.warning('Ada file melebihi 5 MB. File tersebut akan ditolak oleh server.', 'Ukuran file');
    }
  });

  // ==========================================================================
  // Submit via AJAX
  // ==========================================================================
  var btnKirim = $('#btn-kirim');
  var btnKirimHtmlAwal = btnKirim.html();

  form.on('submit', function (e) {
    e.preventDefault();

    btnKirim.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Mengirim...');

    $.ajax({
      url: form.attr('action'),
      type: 'POST',
      data: new FormData(form[0]),
      processData: false,
      contentType: false,
      dataType: 'json'
    })
      .done(function (res) {
        $('#hasil-kode').text(res.kode_pengaduan);
        $('#hasil-pin').text(res.pin_akses);
        $('#kartu-form').addClass('d-none');
        $('#kartu-berhasil').removeClass('d-none');
        window.scrollTo({ top: 0, behavior: 'smooth' });
        toastr.success('Pengaduan berhasil dikirim. Simpan kode & PIN kamu.', 'Berhasil');
      })
      .fail(function (xhr) {
        if (xhr.status === 422) {
          var errors = (xhr.responseJSON && xhr.responseJSON.errors) || {};
          var fields = Object.keys(errors);

          // Lompat ke langkah pertama yang masih memiliki error
          // (field array seperti "bukti.0" dipetakan ke input "bukti[]")
          if (fields.length) {
            var namaField = fields[0].split('.')[0];
            var paneEl = form
              .find('[name="' + namaField + '[]"], [name="' + namaField + '"]')
              .closest('.bs-stepper-pane')[0];
            var semuaPane = stepperEl.querySelectorAll('.bs-stepper-pane');
            var index = Array.prototype.indexOf.call(semuaPane, paneEl);
            if (index > -1) {
              stepper.to(index + 1);
            }
          }

          var pesanPertama = fields.length && errors[fields[0]].length ? errors[fields[0]][0] : null;
          toastr.error(pesanPertama || 'Periksa kembali isian formulir.', 'Gagal mengirim');
        } else {
          toastr.error('Terjadi kendala pada server. Silakan coba beberapa saat lagi.', 'Gagal mengirim');
        }
      })
      .always(function () {
        btnKirim.prop('disabled', false).html(btnKirimHtmlAwal);
      });
  });

  // ==========================================================================
  // Salin kode pengaduan & PIN
  // ==========================================================================
  function salinKeClipboard(teks) {
    if (navigator.clipboard && navigator.clipboard.writeText) {
      return navigator.clipboard.writeText(teks);
    }

    // Fallback untuk browser lama / konteks non-secure
    var areaTeks = document.createElement('textarea');
    areaTeks.value = teks;
    areaTeks.style.position = 'fixed';
    areaTeks.style.opacity = '0';
    document.body.appendChild(areaTeks);
    areaTeks.select();
    document.execCommand('copy');
    areaTeks.remove();
    return Promise.resolve();
  }

  $('.btn-copy').on('click', function () {
    var target = $(this).data('copy') === 'pin' ? $('#hasil-pin') : $('#hasil-kode');
    salinKeClipboard(target.text().trim()).then(function () {
      toastr.success('Tersalin ke clipboard.', 'Berhasil');
    });
  });

  // Buat laporan baru
  $('#btn-lapor-lagi').on('click', function () {
    window.location.reload();
  });
});
