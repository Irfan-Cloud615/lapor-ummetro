'use strict';

$(function () {
  var $table = $('.dt-table-riwayat');
  var dtTable;

  $.ajaxSetup({
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') || CSRF_TOKEN }
  });

  if ($table.length) {
    dtTable = $table.DataTable({
      processing: true,
      serverSide: true,
      ajax: { url: baseUrl + 'admin/riwayat-pengaduan/datatable' },
      columns: [
        { data: 'no', searchable: false, orderable: false },
        { data: 'kode_pengaduan' },
        { data: 'pelapor' },
        { data: 'kategori' },
        { data: 'tanggal_kejadian' },
        { data: 'status_pengaduan' },
        { data: 'id', searchable: false, orderable: false }
      ],
      columnDefs: [
        {
          targets: 1,
          render: function (data) {
            return `<span class="fw-semibold text-primary">#${data}</span>`;
          }
        },
        {
          targets: 5,
          render: function (data) {
            var statusMap = {
              Baru: { label: 'Baru', cls: 'bg-label-info' },
              Diproses: { label: 'Diproses', cls: 'bg-label-primary' },
              Investigasi: { label: 'Investigasi', cls: 'bg-label-warning' },
              Selesai: { label: 'Selesai', cls: 'bg-label-success' },
              Ditolak: { label: 'Ditolak', cls: 'bg-label-danger' }
            };
            var st = statusMap[data] || { label: data, cls: 'bg-label-secondary' };
            return `<span class="badge ${st.cls}">${st.label}</span>`;
          }
        },
        {
          targets: 6,
          render: function (data, type, full) {
            return (
              '<div class="d-flex gap-1">' +
              `<button class="btn btn-sm btn-icon btn-primary on-detail" data-id="${full.id}" title="Lihat Detail">` +
              '<i class="ti ti-eye"></i></button>' +
              '</div>'
            );
          }
        }
      ],
      order: [[1, 'desc']],
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
        searchPlaceholder: 'Cari riwayat pengaduan...',
        processing: '<div class="spinner-border spinner-border-sm text-primary" role="status"></div>',
        emptyTable: 'Tidak ada data riwayat pengaduan.',
        zeroRecords: 'Riwayat pengaduan tidak ditemukan.',
        infoEmpty: 'Menampilkan 0 sampai 0 dari 0 data',
        info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data'
      },
      buttons: []
    });
  }

  // Klik tombol ikon mata → Tampilkan modal detail pengaduan
  $table.on('click', '.on-detail', function () {
    var ticketId = $(this).data('id');
    var url = DETAIL_BASE_URL.replace(':id', ticketId);

    $('#modal-description').html(
      '<div class="spinner-border spinner-border-sm text-primary" role="status"></div> Mengambil data pengaduan...'
    );
    $('#modal-attachment-area').html('<div class="spinner-border spinner-border-sm text-primary" role="status"></div>');
    $('#ticketDetailModal').modal('show');
    $('button.btn-delete').hide();
    $('#group-change-status').hide();

    $.ajax({
      url: url,
      method: 'GET',
      success: function (res) {
        if (res.success) {
          var p = res.pengaduan;

          $('#modal-kode-pengaduan').text('#' + p.kode_pengaduan);
          $('#modal-ticket-title').text('Pengaduan #' + p.kode_pengaduan);

          // Pelapor
          var pelaporNama = p.pelapor ? p.pelapor.nama_lengkap : '-';
          $('#modal-reporter-name').text(pelaporNama);
          $('#modal-pelapor-nama').text(pelaporNama);
          $('#modal-reporter-initial').text(pelaporNama.charAt(0).toUpperCase());
          $('#modal-reporter-status, #modal-pelapor-status-text').text(p.pelapor ? p.pelapor.status_pelapor : '-');
          $('#modal-pelapor-npm-nip').text(p.pelapor && p.pelapor.npm_nip ? p.pelapor.npm_nip : '-');
          $('#modal-pelapor-wa').text(p.pelapor && p.pelapor.kontak_wa ? p.pelapor.kontak_wa : '-');

          // Korban
          $('#modal-korban-nama').text(p.korban ? p.korban.nama_lengkap : '-');
          $('#modal-korban-prodi').text(p.korban ? p.korban.program_studi : '-');
          $('#modal-korban-usia').text(p.korban && p.korban.usia ? p.korban.usia + ' Tahun' : '-');

          // Terlapor
          $('#modal-terlapor-nama').text(p.terlapor ? p.terlapor.nama_lengkap : '-');
          $('#modal-terlapor-status').text(p.terlapor ? p.terlapor.status_terlapor : '-');

          // Rincian Kejadian
          var waktuKejadian = p.tanggal_kejadian + (p.waktu_kejadian ? ' Pukul ' + p.waktu_kejadian : '');
          $('#modal-waktu-kejadian').text(waktuKejadian);
          $('#modal-lokasi-kejadian').text(p.lokasi_kejadian || '-');
          $('#modal-saksi').text(p.saksi || '-');
          $('#modal-description').text(p.kronologi || 'Kronologi belum tersedia.');
          $('#modal-created-at').text(p.created_at);

          // Status Badge
          var statusMap = {
            Baru: { label: 'Baru', cls: 'bg-label-info' },
            Diproses: { label: 'Diproses', cls: 'bg-label-primary' },
            Investigasi: { label: 'Investigasi', cls: 'bg-label-warning' },
            Selesai: { label: 'Selesai', cls: 'bg-label-success' },
            Ditolak: { label: 'Ditolak', cls: 'bg-label-danger' }
          };
          var st = statusMap[p.status_pengaduan] || { label: p.status_pengaduan, cls: 'bg-label-secondary' };
          $('#modal-status-badge')
            .text('Status: ' + st.label)
            .attr('class', 'badge ' + st.cls);
          $('#modal-category-badge').text('Kategori: ' + (p.kategori || '-'));

          // Satgas Penanggung Jawab
          if (p.satgas) {
            $('#modal-assigned-to').html(
              `<span class="badge bg-label-success"><i class="ti ti-user-check me-1"></i> ${p.satgas.name}</span>`
            );
          } else {
            $('#modal-assigned-to').html('<span class="badge bg-label-warning">Belum Ditugaskan</span>');
          }

          // Lampiran Bukti
          if (p.lampiran_bukti && p.lampiran_bukti.length > 0) {
            var html = '<div class="d-flex flex-wrap gap-2 justify-content-center">';
            p.lampiran_bukti.forEach(function (att) {
              if (att.is_image) {
                html += `<a href="${att.file_path}" target="_blank">
                           <img src="${att.file_path}" class="img-thumbnail" style="max-height: 150px;" alt="${att.file_name}">
                         </a>`;
              } else {
                html += `<a href="${att.file_path}" target="_blank" class="btn btn-outline-primary btn-sm">
                           <i class="ti ti-download me-1"></i> ${att.file_name}
                         </a>`;
              }
            });
            html += '</div>';
            $('#modal-attachment-area').html(html).removeClass('text-center');
          } else {
            $('#modal-attachment-area')
              .html(
                `
                  <i class="ti ti-photo-off fs-1 text-muted mb-2 d-block"></i>
                  <p class="text-muted small mb-0">Tidak ada lampiran bukti yang diunggah.</p>
                `
              )
              .addClass('text-center');
          }
        }
      },
      error: function () {
        $('#modal-description').text('Gagal memuat detail pengaduan.');
        $('#modal-attachment-area').html('<p class="text-danger small mb-0">Gagal memuat lampiran bukti.</p>');
      }
    });
  });

  setTimeout(function () {
    $('.dataTables_filter .form-control').removeClass('form-control-sm');
    $('.dataTables_length .form-select').removeClass('form-select-sm');
  }, 300);
});
