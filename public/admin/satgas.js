'use strict';

$(function () {
  var $table = $('.dt-table-satgas');
  var $modalForm = $('#modalSatgas');
  var dtTable;

  $.ajaxSetup({
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
  });

  // Toggle password visibility
  $('#toggle-password').on('click', function () {
    var $input = $('#satgas-password');
    var $icon = $('#password-eye-icon');
    if ($input.attr('type') === 'password') {
      $input.attr('type', 'text');
      $icon.removeClass('ti-eye').addClass('ti-eye-off');
    } else {
      $input.attr('type', 'password');
      $icon.removeClass('ti-eye-off').addClass('ti-eye');
    }
  });

  if ($table.length) {
    dtTable = $table.DataTable({
      processing: true,
      serverSide: true,
      ajax: { url: baseUrl + 'admin/satgas/datatable' },
      columns: [
        { data: 'no', searchable: false, orderable: false },
        { data: 'name' },
        { data: 'email' },
        { data: 'created_at', searchable: false, orderable: false },
        { data: 'id', searchable: false, orderable: false }
      ],
      columnDefs: [
        {
          targets: 4,
          render: function (data, type, full) {
            return (
              '<div class="d-flex align-items-center gap-2">' +
              `<a href="javascript:void(0);" class="text-primary on-view" data-id="${full.id}" title="Lihat Detail"><i class="ti ti-eye fs-5"></i></a>` +
              `<a href="javascript:void(0);" class="text-warning on-edit" data-id="${full.id}" title="Edit Satgas"><i class="ti ti-edit fs-5"></i></a>` +
              `<a href="javascript:void(0);" class="text-danger on-delete" data-id="${full.id}" data-name="${full.name}" title="Hapus Satgas"><i class="ti ti-trash fs-5"></i></a>` +
              '</div>'
            );
          }
        }
      ],
      order: [[1, 'asc']],
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
        searchPlaceholder: 'Cari satgas...',
        processing: '<div class="spinner-border spinner-border-sm text-primary" role="status"></div>',
        emptyTable: 'Tidak ada data satgas.',
        zeroRecords: 'Satgas tidak ditemukan.',
        infoEmpty: 'Menampilkan 0 sampai 0 dari 0 data',
        info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data'
      },
      buttons: [
        {
          text: '<i class="ti ti-plus me-1"></i>Tambah Satgas',
          className: 'btn btn-primary btn-sm ms-2',
          action: function () {
            openModalTambah();
          }
        }
      ]
    });
  }

  function resetForm() {
    $('#form-satgas')[0].reset();
    $('#satgas-id').val('');
    $('#form-satgas').find('input').prop('disabled', false);
    $('#btn-simpan-satgas').removeClass('d-none');
    $('#satgas-name').removeClass('is-invalid');
    $('#satgas-email').removeClass('is-invalid');
    $('#satgas-password').removeClass('is-invalid').attr('type', 'password');
    $('#password-eye-icon').removeClass('ti-eye-off').addClass('ti-eye');
    $('#error-name').text('');
    $('#error-email').text('');
    $('#error-password').text('');
    // Reset password field label
    $('#password-required').show();
    $('#password-hint').hide();
    $('#satgas-password').attr('placeholder', 'Masukkan password');
  }

  function openModalTambah() {
    resetForm();
    $('#modalSatgasTitle').text('Tambah Satgas');
    $('#btn-simpan-satgas').text('Simpan');
    $modalForm.modal('show');
  }

  // View (read-only)
  $table.on('click', '.on-view', function () {
    var id = $(this).data('id');

    $.get(
      baseUrl + 'admin/satgas/datatable',
      { search: { value: '' }, draw: 1, start: 0, length: 10000 },
      function (res) {
        var row = res.data.find(function (r) {
          return r.id == id;
        });
        if (!row) return;

        resetForm();
        $('#modalSatgasTitle').text('Detail Satgas');
        $('#satgas-id').val(row.id);
        $('#satgas-name').val(row.name).prop('disabled', true);
        $('#satgas-email').val(row.email).prop('disabled', true);
        $('#satgas-password').val('').prop('disabled', true).attr('placeholder', '(tersembunyi)');
        $('#toggle-password').off('click');
        $('#btn-simpan-satgas').addClass('d-none');
        $modalForm.modal('show');
      }
    );
  });

  // Edit
  $table.on('click', '.on-edit', function () {
    var id = $(this).data('id');

    $.get(
      baseUrl + 'admin/satgas/datatable',
      { search: { value: '' }, draw: 1, start: 0, length: 10000 },
      function (res) {
        var row = res.data.find(function (r) {
          return r.id == id;
        });
        if (!row) return;

        resetForm();
        $('#modalSatgasTitle').text('Edit Satgas');
        $('#btn-simpan-satgas').text('Perbarui');
        $('#satgas-id').val(row.id);
        $('#satgas-name').val(row.name);
        $('#satgas-email').val(row.email);
        // Password optional on edit
        $('#password-required').hide();
        $('#password-hint').show();
        $('#satgas-password').attr('placeholder', 'Kosongkan jika tidak diubah');
        $modalForm.modal('show');
      }
    );
  });

  // Submit (add/edit)
  $('#form-satgas').on('submit', function (e) {
    e.preventDefault();

    var id = $('#satgas-id').val();
    var url = id ? baseUrl + 'admin/satgas/' + id : baseUrl + 'admin/satgas';
    var method = id ? 'PUT' : 'POST';
    var $btn = $('#btn-simpan-satgas');

    // Clear previous errors
    $('#satgas-name').removeClass('is-invalid');
    $('#satgas-email').removeClass('is-invalid');
    $('#satgas-password').removeClass('is-invalid');
    $('#error-name').text('');
    $('#error-email').text('');
    $('#error-password').text('');

    $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Menyimpan...');

    var payload = {
      name: $('#satgas-name').val(),
      email: $('#satgas-email').val()
    };
    var pass = $('#satgas-password').val();
    if (pass) payload.password = pass;

    $.ajax({
      url: url,
      method: method,
      data: payload,
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
          if (errors.name) {
            $('#satgas-name').addClass('is-invalid');
            $('#error-name').text(errors.name[0]);
          }
          if (errors.email) {
            $('#satgas-email').addClass('is-invalid');
            $('#error-email').text(errors.email[0]);
          }
          if (errors.password) {
            $('#satgas-password').addClass('is-invalid');
            $('#error-password').text(errors.password[0]);
          }
        } else {
          Swal.fire({ icon: 'error', title: 'Gagal!', text: 'Terjadi kesalahan. Silakan coba lagi.' });
        }
      },
      complete: function () {
        $btn.prop('disabled', false).text(id ? 'Perbarui' : 'Simpan');
      }
    });
  });

  // Delete
  $table.on('click', '.on-delete', function () {
    var id = $(this).data('id');
    var name = $(this).data('name');

    Swal.fire({
      title: 'Hapus Satgas?',
      html: `Apakah Anda yakin ingin menghapus satgas <strong>"${name}"</strong>?<br><small class="text-muted">Tindakan ini tidak dapat dibatalkan.</small>`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Ya, Hapus!',
      cancelButtonText: 'Batal',
      showLoaderOnConfirm: true,
      preConfirm: function () {
        return $.ajax({
          url: baseUrl + 'admin/satgas/' + id,
          method: 'DELETE'
        }).catch(function () {
          Swal.showValidationMessage('Gagal menghapus satgas. Silakan coba lagi.');
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
          text: 'Akun satgas berhasil dihapus.',
          timer: 2000,
          showConfirmButton: false,
          timerProgressBar: true
        });
      }
    });
  });

  $modalForm.on('hidden.bs.modal', function () {
    resetForm();
    // Re-bind toggle after view mode unbinds it
    $('#toggle-password')
      .off('click')
      .on('click', function () {
        var $input = $('#satgas-password');
        var $icon = $('#password-eye-icon');
        if ($input.attr('type') === 'password') {
          $input.attr('type', 'text');
          $icon.removeClass('ti-eye').addClass('ti-eye-off');
        } else {
          $input.attr('type', 'password');
          $icon.removeClass('ti-eye-off').addClass('ti-eye');
        }
      });
  });

  setTimeout(function () {
    $('.dataTables_filter .form-control').removeClass('form-control-sm');
    $('.dataTables_length .form-select').removeClass('form-select-sm');
  }, 300);
});
