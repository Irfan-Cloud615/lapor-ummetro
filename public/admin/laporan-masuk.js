$(document).ready(function () {
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN':
        $('meta[name="csrf-token"]').attr('content') || (typeof CSRF_TOKEN !== 'undefined' ? CSRF_TOKEN : '')
    }
  });

  let draggedCard = null;

  updateUnassignedCount();

  // ── Helpers ──────────────────────────────────────────────────────────
  function getCardData($card) {
    return {
      id: $card.data('ticket-id'),
      number: $card.data('ticket-number'),
      title: $card.data('title'),
      status: $card.data('status'),
      statusLabel: $card.data('status-label'),
      statusCls: $card.data('status-cls'),
      category: $card.data('category'),
      reporter: $card.data('reporter'),
      description: $card.data('description'),
      created: $card.data('created')
    };
  }

  /**
   * Sisipkan kartu ke dalam ticket-stack (carousel/slider).
   * Jika belum ada stack, buat dulu.
   */
  function pushCardToStack($zone, $card) {
    const $prevStaffCol = $card.closest('.staff-col');
    $zone.find('.zone-empty').remove();

    const staffId = $zone.closest('.staff-col').data('staff-id');
    let $wrapper = $zone.find('.ticket-stack-wrapper');

    if ($wrapper.length === 0) {
      $wrapper = $(`
                <div class="ticket-stack-wrapper">
                    <div class="ticket-stack">
                        <div class="stack-inner" id="stack-${staffId}" data-current="0" data-total="0"></div>
                    </div>
                    <div class="stack-nav">
                        <button class="stack-btn" data-stack-id="stack-${staffId}" data-dir="-1">‹ Prev</button>
                        <span class="stack-counter" id="counter-${staffId}">0 / 0</span>
                        <button class="stack-btn" data-stack-id="stack-${staffId}" data-dir="1">Next ›</button>
                    </div>
                </div>
            `);
      $zone.append($wrapper);
    }

    const $inner = $wrapper.find('.stack-inner');

    $card.addClass('border border-success').removeClass('border-0 mb-3');
    $card.css({
      flex: '0 0 100%',
      'scroll-snap-align': 'start',
      'margin-bottom': 0,
      height: '100%',
      overflow: 'hidden'
    });

    $inner.append($card);

    const totalCards = $inner.children('.kanban-ticket-card').length;
    const current = totalCards - 1;

    $inner.attr('data-total', totalCards);
    $inner.attr('data-current', current);
    $inner.css('transform', `translateX(-${current * 100}%)`);

    $(`#counter-${staffId}`).text(`${current + 1} / ${totalCards}`);

    if ($prevStaffCol.length && !$prevStaffCol.is($zone.closest('.staff-col'))) {
      updateStaffCount($prevStaffCol);
    }
    updateUnassignedCount();
  }

  // ── Drag Start ───────────────────────────────────────────────────────
  $(document).on('dragstart', '.kanban-ticket-card', function (e) {
    draggedCard = this;
    $(this).addClass('dragging');
    e.originalEvent.dataTransfer.effectAllowed = 'move';
    e.originalEvent.dataTransfer.setData('text/plain', $(this).data('ticket-id'));
  });

  $(document).on('dragend', '.kanban-ticket-card', function () {
    $(this).removeClass('dragging');
    draggedCard = null;
    $('.kanban-drop-zone, .staff-col-body').removeClass('drag-over');
  });

  // ── Drag Over / Leave ────────────────────────────────────────────────
  $(document).on('dragover', '.kanban-drop-zone', function (e) {
    e.preventDefault();
    e.originalEvent.dataTransfer.dropEffect = 'move';
    $(this).addClass('drag-over');
  });

  $(document).on('dragleave', '.kanban-drop-zone', function () {
    $(this).removeClass('drag-over');
  });

  // ── Drop ─────────────────────────────────────────────────────────────
  $(document).on('drop', '.kanban-drop-zone', function (e) {
    e.preventDefault();
    $(this).removeClass('drag-over');

    if (!draggedCard) return;

    const $zone = $(this);
    const $card = $(draggedCard);
    const colType = $zone.data('column-type') || $zone.closest('[data-column-type]').data('column-type');
    const $staffCol = $zone.closest('.staff-col');

    if (colType === 'unassigned') {
      const $prevStaffCol = $card.closest('.staff-col');
      $card.find('.assigned-badge').remove();
      $card.removeAttr('data-assigned-to');
      $zone.append($card);
      updateUnassignedCount();
      if ($prevStaffCol.length) updateStaffCount($prevStaffCol);
    } else {
      const satgasId = $staffCol.data('staff-id');
      const satgasName = $staffCol.data('staff-name');
      const ticketId = $card.data('ticket-id');
      const ticketNum = $card.data('ticket-number');
      const currentAssignee = $card.attr('data-assigned-to');

      if (currentAssignee == satgasId) {
        pushCardToStack($zone, $card);
        return;
      }

      Swal.fire({
        title: 'Konfirmasi Penugasan',
        html: `Apakah Anda yakin ingin menugaskan pengaduan <strong>${ticketNum}</strong> kepada Satgas <strong>${satgasName}</strong>?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Tugaskan!',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#7367f0',
        cancelButtonColor: '#ea5455'
      }).then(result => {
        if (!result.isConfirmed) return;

        const url = ASSIGN_BASE_URL.replace(':id', ticketId);
        $.ajax({
          url,
          method: 'POST',
          data: {
            _token: CSRF_TOKEN,
            satgas_id: satgasId
          },
          success: function (res) {
            if (res.success) {
              $card.attr('data-assigned-to', satgasId);
              pushCardToStack($zone, $card);
              updateUnassignedCount();
              updateStaffCount($staffCol);

              Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: res.message,
                timer: 2000,
                showConfirmButton: false
              });
            }
          },
          error: function () {
            Swal.fire('Gagal', 'Terjadi kesalahan saat menyimpan penugasan Satgas.', 'error');
          }
        });
      });
    }
  });

  // ── Stack Navigation (Prev / Next) ───────────────────────────────────
  $(document).on('click', '.stack-btn', function () {
    const stackId = $(this).data('stack-id');
    const dir = parseInt($(this).data('dir'));
    const $inner = $('#' + stackId);

    let current = parseInt($inner.attr('data-current') || 0);
    let total = $inner.children('.kanban-ticket-card').length;

    if (total === 0) return;

    let next = current + dir;
    if (next < 0) next = total - 1;
    if (next >= total) next = 0;

    $inner.attr('data-current', next);
    $inner.css('transform', `translateX(-${next * 100}%)`);

    const staffId = stackId.replace('stack-', '');
    $(`#counter-${staffId}`).text(`${next + 1} / ${total}`);
  });

  // ── Update count badges & card stacks ─────────────────────────────────
  function updateUnassignedCount() {
    const $zone = $('#zone-unassigned');
    const $cards = $zone.find('.kanban-ticket-card');
    const count = $cards.length;

    $('#badge-unassigned').text(count);

    if (count === 0) {
      $zone.find('.ticket-stack-wrapper').remove();
      if ($zone.find('.zone-empty').length === 0) {
        $zone.append(`
          <div class="zone-empty">
            <i class="ti ti-checks fs-2 mb-1"></i>
            <span>Semua pengaduan sudah ditugaskan!</span>
          </div>
        `);
      }
      return;
    }

    $zone.find('.zone-empty').remove();

    if (count > 2) {
      let $wrapper = $zone.find('.ticket-stack-wrapper');
      if ($wrapper.length === 0) {
        $wrapper = $(`
          <div class="ticket-stack-wrapper">
            <div class="ticket-stack">
              <div class="stack-inner" id="stack-unassigned" data-current="0" data-total="0"></div>
            </div>
            <div class="stack-nav">
              <button class="stack-btn" data-stack-id="stack-unassigned" data-dir="-1">‹ Prev</button>
              <span class="stack-counter" id="counter-unassigned">1 / 1</span>
              <button class="stack-btn" data-stack-id="stack-unassigned" data-dir="1">Next ›</button>
            </div>
          </div>
        `);
        $zone.append($wrapper);
      }

      const $inner = $wrapper.find('.stack-inner');

      $cards.each(function () {
        const $c = $(this);
        $c.css({
          flex: '0 0 100%',
          'scroll-snap-align': 'start',
          'margin-bottom': 0,
          height: '100%',
          overflow: 'hidden'
        });
        $c.removeClass('border border-success').addClass('border-0');
        if (!$c.parent().is($inner)) {
          $inner.append($c);
        }
      });

      let current = parseInt($inner.attr('data-current') || 0);
      if (current >= count) current = count - 1;
      if (current < 0) current = 0;

      $inner.attr('data-total', count);
      $inner.attr('data-current', current);
      $inner.css('transform', `translateX(-${current * 100}%)`);

      $('#counter-unassigned').text(`${current + 1} / ${count}`);
    } else {
      const $wrapper = $zone.find('.ticket-stack-wrapper');
      if ($wrapper.length > 0) {
        $cards.each(function () {
          const $c = $(this);
          $c.css({
            flex: '',
            'scroll-snap-align': '',
            'margin-bottom': '.75rem',
            height: '',
            overflow: ''
          });
          $c.removeClass('border border-success').addClass('border-0 mb-3');
          $zone.append($c);
        });
        $wrapper.remove();
      } else {
        $cards.css({
          flex: '',
          'scroll-snap-align': '',
          'margin-bottom': '.75rem',
          height: '',
          overflow: ''
        });
        $cards.removeClass('border border-success').addClass('border-0 mb-3');
      }
    }
  }

  function updateStaffCount($staffCol) {
    const $zone = $staffCol.find('.staff-col-body');
    const $cards = $zone.find('.kanban-ticket-card');
    const count = $cards.length;
    const staffId = $staffCol.data('staff-id');

    $staffCol.find('.staff-count').text(count);

    if (count === 0) {
      $zone.find('.ticket-stack-wrapper').remove();
      if ($zone.find('.zone-empty').length === 0) {
        $zone.append(`
          <div class="zone-empty">
            <i class="ti ti-drag-drop fs-2 mb-1"></i>
            <span>Seret pengaduan ke sini</span>
          </div>
        `);
      }
    } else {
      const $inner = $zone.find('.stack-inner');
      if ($inner.length) {
        let current = parseInt($inner.attr('data-current') || 0);
        if (current >= count) current = count - 1;
        if (current < 0) current = 0;

        $inner.attr('data-total', count);
        $inner.attr('data-current', current);
        $inner.css('transform', `translateX(-${current * 100}%)`);

        $(`#counter-${staffId}`).text(`${current + 1} / ${count}`);
      }
    }
  }

  // ── Klik kartu → Modal Detail ─────────────────────────────────────────
  $(document).on('click', '.kanban-ticket-card', function () {
    const ticketId = $(this).data('ticket-id');
    const url = DETAIL_BASE_URL.replace(':id', ticketId);

    $('#modal-description').html(
      '<div class="spinner-border spinner-border-sm text-primary" role="status"></div> Mengambil data pengaduan...'
    );
    $('#modal-attachment-area').html('<div class="spinner-border spinner-border-sm text-primary" role="status"></div>');
    $('#ticketDetailModal').modal('show');

    // Simpan active pengaduan ID di tombol hapus & ubah status
    $('#btn-delete-ticket').data('ticket-id', ticketId);
    $('.btn-set-status').data('ticket-id', ticketId);

    $.ajax({
      url: url,
      method: 'GET',
      success: function (res) {
        if (res.success) {
          const p = res.pengaduan;

          $('#modal-kode-pengaduan').text('#' + p.kode_pengaduan);
          $('#modal-ticket-title').text('Pengaduan #' + p.kode_pengaduan);

          // Pelapor
          const pelaporNama = p.pelapor ? p.pelapor.nama_lengkap : '-';
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
          const waktuKejadian = p.tanggal_kejadian + (p.waktu_kejadian ? ' Pukul ' + p.waktu_kejadian : '');
          $('#modal-waktu-kejadian').text(waktuKejadian);
          $('#modal-lokasi-kejadian').text(p.lokasi_kejadian || '-');
          $('#modal-saksi').text(p.saksi || '-');
          $('#modal-description').text(p.kronologi || 'Kronologi belum tersedia.');
          $('#modal-created-at').text(p.created_at);

          // Status Badge
          let statusMap = {
            Baru: { label: 'Baru', cls: 'bg-label-info' },
            Diproses: { label: 'Diproses', cls: 'bg-label-primary' },
            Investigasi: { label: 'Investigasi', cls: 'bg-label-warning' },
            Selesai: { label: 'Selesai', cls: 'bg-label-success' },
            Ditutup: { label: 'Ditutup', cls: 'bg-label-secondary' },
            Ditolak: { label: 'Ditolak', cls: 'bg-label-danger' }
          };
          let st = statusMap[p.status_pengaduan] || { label: p.status_pengaduan, cls: 'bg-label-secondary' };
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
            let html = '<div class="d-flex flex-wrap gap-2 justify-content-center">';
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

  // ── Ubah Status Pengaduan ─────────────────────────────────────────────
  $(document).on('click', '.btn-set-status', function () {
    const ticketId = $(this).data('ticket-id');
    const newStatus = $(this).data('status');
    if (!ticketId || !newStatus) return;

    Swal.fire({
      title: 'Ubah Status Pengaduan?',
      html: `Ubah status pengaduan menjadi <strong>${newStatus}</strong>?`,
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'Ya, Ubah!',
      cancelButtonText: 'Batal',
      confirmButtonColor: '#7367f0',
      cancelButtonColor: '#6c757d'
    }).then(function (result) {
      if (!result.isConfirmed) return;

      const url = STATUS_BASE_URL.replace(':id', ticketId);
      $.ajax({
        url: url,
        method: 'POST',
        data: {
          _token: CSRF_TOKEN,
          status_pengaduan: newStatus
        },
        success: function (res) {
          if (res.success) {
            $('#ticketDetailModal').modal('hide');
            Swal.fire({
              icon: 'success',
              title: 'Berhasil!',
              text: res.message,
              timer: 2000,
              showConfirmButton: false
            });

            // Refresh halaman atau reload elemen jika perlu
            setTimeout(function () {
              window.location.reload();
            }, 1000);
          }
        },
        error: function () {
          Swal.fire('Gagal', 'Gagal memperbarui status pengaduan.', 'error');
        }
      });
    });
  });

  // ── Hapus Pengaduan ──────────────────────────────────────────────────
  $(document).on('click', '#btn-delete-ticket', function () {
    const ticketId = $(this).data('ticket-id');
    if (!ticketId) return;

    Swal.fire({
      title: 'Hapus Pengaduan?',
      html: 'Apakah Anda yakin ingin menghapus pengaduan ini?<br><small class="text-muted">Tindakan ini tidak dapat dibatalkan.</small>',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Ya, Hapus!',
      cancelButtonText: 'Batal',
      confirmButtonColor: '#ea5455',
      cancelButtonColor: '#6c757d',
      showLoaderOnConfirm: true,
      preConfirm: function () {
        const url = DELETE_BASE_URL.replace(':id', ticketId);
        return $.ajax({
          url: url,
          method: 'DELETE',
          data: { _token: CSRF_TOKEN }
        }).catch(function (xhr) {
          var msg =
            xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Gagal menghapus pengaduan.';
          Swal.showValidationMessage(msg);
        });
      },
      allowOutsideClick: function () {
        return !Swal.isLoading();
      }
    }).then(function (result) {
      if (result.isConfirmed && result.value && result.value.success) {
        $('#ticketDetailModal').modal('hide');
        Swal.fire({
          icon: 'success',
          title: 'Terhapus!',
          text: result.value.message || 'Pengaduan berhasil dihapus.',
          timer: 2000,
          showConfirmButton: false,
          timerProgressBar: true
        });

        const $card = $(`.kanban-ticket-card[data-ticket-id="${ticketId}"]`);
        if ($card.length) {
          const $parentCol = $card.closest('.staff-col');
          $card.fadeOut(300, function () {
            $(this).remove();
            updateUnassignedCount();
            if ($parentCol.length) updateStaffCount($parentCol);
          });
        } else {
          window.location.reload();
        }
      }
    });
  });

  // function updateTimers() {
  //   $('.timer-display').each(function () {
  //     let assignedAt = $(this).attr('data-assigned-at');
  //     if (!assignedAt) return;

  //     let assignedTime = new Date(assignedAt).getTime();
  //     let now = new Date().getTime();
  //     let diff = now - assignedTime;

  //     if (diff < 0) return;

  //     let hours = Math.floor(diff / (1000 * 60 * 60));
  //     let minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
  //     let seconds = Math.floor((diff % (1000 * 60)) / 1000);

  //     let timeString = '';
  //     if (hours > 0) timeString += hours + 'j ';
  //     if (minutes > 0 || hours > 0) timeString += minutes + 'm ';
  //     timeString += seconds + 'd';

  //     $(this).text('Dikerjakan: ' + timeString);
  //   });
  // }

  setInterval(updateTimers, 1000);
  updateTimers();
});
