'use strict';

$(function () {
  var $table = $('.dt-table-category');
  var $modalForm = $('#modalKategori');
  var dtTable;

  $.ajaxSetup({
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
  });

  if ($table.length) {
    dtTable = $table.DataTable({
      processing: true,
      serverSide: true,
      ajax: { url: baseUrl + 'admin/kategori-kasus/datatable' },
      columns: [
        { data: 'no', searchable: false, orderable: false },
        { data: 'nama_kategori' },
        { data: 'is_active', searchable: false, orderable: false },
        { data: 'created_at', searchable: false },
        { data: 'id', searchable: false, orderable: false }
      ],
      columnDefs: [
        {
          targets: 2,
          render: function (data) {
            return data == 1
              ? '<span class="badge bg-label-success">Aktif</span>'
              : '<span class="badge bg-label-secondary">Tidak Aktif</span>';
          }
        },
        {
          targets: 4,
          render: function (data, type, full) {
            return (
              '<div class="d-flex gap-1">' +
              `<button class="btn btn-sm btn-icon btn-warning on-edit" data-id="${full.id}" title="Edit">` +
              '<i class="ti ti-edit"></i></button>' +
              `<button class="btn btn-sm btn-icon btn-danger on-delete" data-id="${full.id}" data-name="${full.nama_kategori}" title="Hapus">` +
              '<i class="ti ti-trash"></i></button>' +
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
        searchPlaceholder: 'Cari kategori kasus...',
        processing: '<div class="spinner-border spinner-border-sm text-primary" role="status"></div>',
        emptyTable: 'Tidak ada data kategori kasus.',
        zeroRecords: 'Kategori kasus tidak ditemukan.',
        infoEmpty: 'Menampilkan 0 sampai 0 dari 0 data',
        info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data'
      },
      buttons: [
        {
          text: '<i class="ti ti-plus me-1"></i>Tambah Kategori',
          className: 'btn btn-primary btn-sm ms-2',
          action: function () {
            openModalTambah();
          }
        }
      ]
    });
  }

  function resetForm() {
    $('#form-kategori')[0].reset();
    $('#kategori-id').val('');
    $('#kategori-nama').removeClass('is-invalid');
    $('#error-nama-kategori').text('');
    $('#kategori-is-active').val('1');
  }

  function openModalTambah() {
    resetForm();
    $('#modalKategoriTitle').text('Tambah Kategori Kasus');
    $('#btn-simpan').text('Simpan');
    $modalForm.modal('show');
  }

  $table.on('click', '.on-edit', function () {
    var id = $(this).data('id');

    $.get(
      baseUrl + 'admin/kategori-kasus/datatable',
      { search: { value: '' }, draw: 1, start: 0, length: 1000 },
      function (res) {
        var row = res.data.find(function (r) {
          return r.id == id;
        });
        if (!row) return;

        resetForm();
        $('#modalKategoriTitle').text('Edit Kategori Kasus');
        $('#btn-simpan').text('Perbarui');
        $('#kategori-id').val(row.id);
        $('#kategori-nama').val(row.nama_kategori);
        $('#kategori-is-active').val(row.is_active);
        $modalForm.modal('show');
      }
    );
  });

  $('#form-kategori').on('submit', function (e) {
    e.preventDefault();

    var id = $('#kategori-id').val();
    var url = id ? baseUrl + 'admin/kategori-kasus/' + id : baseUrl + 'admin/kategori-kasus';
    var method = id ? 'PUT' : 'POST';
    var $btn = $('#btn-simpan');

    $('#kategori-nama').removeClass('is-invalid');
    $('#error-nama-kategori').text('');

    $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Menyimpan...');

    $.ajax({
      url: url,
      method: method,
      data: {
        nama_kategori: $('#kategori-nama').val(),
        is_active: $('#kategori-is-active').val()
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
          if (errors.nama_kategori) {
            $('#kategori-nama').addClass('is-invalid');
            $('#error-nama-kategori').text(errors.nama_kategori[0]);
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
      title: 'Hapus Kategori Kasus?',
      html: `Apakah Anda yakin ingin menghapus kategori <strong>${name}</strong>?<br><small class="text-muted">Tindakan ini tidak dapat dibatalkan.</small>`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Ya, Hapus!',
      cancelButtonText: 'Batal',
      showLoaderOnConfirm: true,
      preConfirm: function () {
        return $.ajax({
          url: baseUrl + 'admin/kategori-kasus/' + id,
          method: 'DELETE'
        }).catch(function () {
          Swal.showValidationMessage('Gagal menghapus kategori. Silakan coba lagi.');
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
          text: 'Kategori kasus berhasil dihapus.',
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
