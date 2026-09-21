'use strict';

$(function () {
  var $table = $('.dt-table-faq');
  var $modalForm = $('#modalFaq');
  var dtTable;

  $.ajaxSetup({
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
  });

  if ($table.length) {
    dtTable = $table.DataTable({
      processing: true,
      serverSide: true,
      ajax: { url: baseUrl + 'admin/faq/datatable' },
      columns: [
        { data: 'no', searchable: false, orderable: false },
        { data: 'pertanyaan' },
        { data: 'jawaban' },
        { data: 'urutan' },
        { data: 'is_active', searchable: false, orderable: false },
        { data: 'id', searchable: false, orderable: false }
      ],
      columnDefs: [
        {
          targets: 1,
          render: function (data) {
            var text = data || '';
            if (text.length > 30) {
              return text.substring(0, 20) + '...';
            }
            return text;
          }
        },
        {
          targets: 2,
          render: function (data) {
            var text = data || '';
            if (text.length > 30) {
              return text.substring(0, 20) + '...';
            }
            return text;
          }
        },
        {
          targets: 3,
          render: function (data) {
            return `<span class="badge bg-label-info">${data}</span>`;
          }
        },
        {
          targets: 4,
          render: function (data) {
            return data == 1
              ? '<span class="badge bg-label-success">Aktif</span>'
              : '<span class="badge bg-label-secondary">Tidak Aktif</span>';
          }
        },
        {
          targets: 5,
          render: function (data, type, full) {
            return (
              '<div class="d-flex align-items-center gap-2">' +
              `<a href="javascript:void(0);" class="text-primary  on-view" data-id="${full.id}" title="Lihat Detail FAQ"><i class="ti ti-eye fs-5"></i></a>` +
              `<a href="javascript:void(0);" class="text-warning on-edit" data-id="${full.id}" title="Edit FAQ"><i class="ti ti-edit fs-5"></i></a>` +
              `<a href="javascript:void(0);" class="text-danger on-delete" data-id="${full.id}" data-name="${full.pertanyaan}" title="Hapus FAQ"><i class="ti ti-trash fs-5"></i></a>` +
              '</div>'
            );
          }
        }
      ],
      order: [[3, 'asc']],
      dom:
        '<"row me-2"' +
        '<"col-md-2"<"me-3"l>>' +
        '<"col-md-10"<"dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-3 mb-md-0"fB>>' +
        '>t' +
        '<"row mx-2"' +
        '<"col-sm-12 col-md-6"i>' +
        '<"col-sm-12 col-md-6"p>' +
        '>',
      language: {
        sLengthMenu: '_MENU_',
        search: '',
        searchPlaceholder: 'Cari FAQ...',
        processing: '<div class="spinner-border spinner-border-sm text-primary" role="status"></div>',
        emptyTable: 'Tidak ada data FAQ.',
        zeroRecords: 'Pertanyaan FAQ tidak ditemukan.',
        infoEmpty: 'Menampilkan 0 sampai 0 dari 0 data',
        info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data'
      },
      buttons: [
        {
          text: '<i class="ti ti-plus me-1"></i>Tambah FAQ',
          className: 'btn btn-primary btn-sm ms-2',
          action: function () {
            openModalTambah();
          }
        }
      ]
    });
  }

  function resetForm() {
    $('#form-faq')[0].reset();
    $('#faq-id').val('');
    $('#form-faq').find('input, textarea, select').prop('disabled', false);
    $('#btn-simpan').removeClass('d-none');
    $('#faq-pertanyaan').removeClass('is-invalid');
    $('#faq-jawaban').removeClass('is-invalid');
    $('#error-pertanyaan').text('');
    $('#error-jawaban').text('');
    $('#faq-urutan').val('0');
    $('#faq-is-active').val('1');
  }

  function openModalTambah() {
    resetForm();
    $('#modalFaqTitle').text('Tambah FAQ');
    $('#btn-simpan').text('Simpan');
    $modalForm.modal('show');
  }

  // Klik Ikon Lihat Detail (View Mode - Disabled Input)
  $table.on('click', '.on-view', function () {
    var id = $(this).data('id');

    $.get(baseUrl + 'admin/faq/datatable', { search: { value: '' }, draw: 1, start: 0, length: 1000 }, function (res) {
      var row = res.data.find(function (r) {
        return r.id == id;
      });
      if (!row) return;

      resetForm();
      $('#modalFaqTitle').text('Detail FAQ');
      $('#faq-id').val(row.id);
      $('#faq-pertanyaan').val(row.pertanyaan).prop('disabled', true);
      $('#faq-jawaban').val(row.jawaban).prop('disabled', true);
      $('#faq-urutan').val(row.urutan).prop('disabled', true);
      $('#faq-is-active').val(row.is_active).prop('disabled', true);
      $('#btn-simpan').addClass('d-none');
      $modalForm.modal('show');
    });
  });

  // Klik Ikon Edit FAQ
  $table.on('click', '.on-edit', function () {
    var id = $(this).data('id');

    $.get(baseUrl + 'admin/faq/datatable', { search: { value: '' }, draw: 1, start: 0, length: 1000 }, function (res) {
      var row = res.data.find(function (r) {
        return r.id == id;
      });
      if (!row) return;

      resetForm();
      $('#modalFaqTitle').text('Edit FAQ');
      $('#btn-simpan').text('Perbarui');
      $('#faq-id').val(row.id);
      $('#faq-pertanyaan').val(row.pertanyaan);
      $('#faq-jawaban').val(row.jawaban);
      $('#faq-urutan').val(row.urutan);
      $('#faq-is-active').val(row.is_active);
      $modalForm.modal('show');
    });
  });

  $('#form-faq').on('submit', function (e) {
    e.preventDefault();

    var id = $('#faq-id').val();
    var url = id ? baseUrl + 'admin/faq/' + id : baseUrl + 'admin/faq';
    var method = id ? 'PUT' : 'POST';
    var $btn = $('#btn-simpan');

    $('#faq-pertanyaan').removeClass('is-invalid');
    $('#faq-jawaban').removeClass('is-invalid');
    $('#error-pertanyaan').text('');
    $('#error-jawaban').text('');

    $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Menyimpan...');

    $.ajax({
      url: url,
      method: method,
      data: {
        pertanyaan: $('#faq-pertanyaan').val(),
        jawaban: $('#faq-jawaban').val(),
        urutan: $('#faq-urutan').val(),
        is_active: $('#faq-is-active').val()
      },
      success: function (res) {
        if (res.success) {
          $modalForm.modal('hide');
          dtTable.ajax.reload(null, false);
          Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: res.message,
            timer: 2000,
            showConfirmButton: false,
            timerProgressBar: true
          });
        }
      },
      error: function (xhr) {
        if (xhr.status === 422) {
          var errors = xhr.responseJSON.errors;
          if (errors.pertanyaan) {
            $('#faq-pertanyaan').addClass('is-invalid');
            $('#error-pertanyaan').text(errors.pertanyaan[0]);
          }
          if (errors.jawaban) {
            $('#faq-jawaban').addClass('is-invalid');
            $('#error-jawaban').text(errors.jawaban[0]);
          }
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: 'Terjadi kesalahan. Silakan coba lagi.'
          });
        }
      },
      complete: function () {
        $btn.prop('disabled', false).text(id ? 'Perbarui' : 'Simpan');
      }
    });
  });

  $table.on('click', '.on-delete', function () {
    var id = $(this).data('id');
    var name = $(this).data('name');

    Swal.fire({
      title: 'Hapus FAQ?',
      html: `Apakah Anda yakin ingin menghapus FAQ <strong>"${name}"</strong>?<br><small class="text-muted">Tindakan ini tidak dapat dibatalkan.</small>`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Ya, Hapus!',
      cancelButtonText: 'Batal',
      showLoaderOnConfirm: true,
      preConfirm: function () {
        return $.ajax({
          url: baseUrl + 'admin/faq/' + id,
          method: 'DELETE'
        }).catch(function () {
          Swal.showValidationMessage('Gagal menghapus FAQ. Silakan coba lagi.');
        });
      },
      allowOutsideClick: function () {
        return !Swal.isLoading();
      }
    }).then(function (result) {
      if (result.isConfirmed) {
        dtTable.ajax.reload(null, false);
        Swal.fire({
          icon: 'success',
          title: 'Terhapus!',
          text: 'Pertanyaan FAQ berhasil dihapus.',
          timer: 2000,
          showConfirmButton: false,
          timerProgressBar: true
        });
      }
    });
  });

  $modalForm.on('hidden.bs.modal', function () {
    resetForm();
  });

  setTimeout(function () {
    $('.dataTables_filter .form-control').removeClass('form-control-sm');
    $('.dataTables_length .form-select').removeClass('form-select-sm');
  }, 300);
});
