<?php
error_reporting('~E_NOTICE~');
$target = "1";
require_once("../server/controllers/class.CtrlGlobal.php");
$objCtrl = new CtrlGlobal();

if ($_GET['msg'] != "") {
  $msg = $_GET['msg'];
} else {
  $msg = "";
};

?>


<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<body>
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
    <?php include 'left_panel.php'; ?>
    <div class="body-wrapper">
      <?php include 'main.php'; ?>
      <div class="container-fluid">
        <div class="row">
          <div class="card shadow mb-4 tble">
            <div class="card-body">
              <h5 class="card-title fw-bold">Riwayat Pemesanan</h5>
              <div class="table-responsive">
                <div class="d-flex justify-content-end mb-3">
                  <button class="btn btn-primary blue add" onclick="$('#act').val('create');"> <i class="ti ti-plus"></i>Tambah</button>
                </div>
                <table id="sample_4" class="table table-striped mb-4 text-center" style="width:100%">
                  <thead>
                    <tr>
                      <th width="2%" class="text-center">No</th>
                      <th width="15%" class="text-center">Jenis Kendaraan</th>
                      <th width="15%" class="text-center">Tipe Kendaraan</th>
                      <th width="18%" class="text-center">Driver</th>
                      <th width="30%" class="text-center">Status</th>
                      <th width="20%" class="text-center">action</th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
          </div>
        </div>
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-md">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Apakah anda yakin?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <input type="text" name="reason" id="reason" class="form-control mt-3" value="" placeholder="Alasan">
                <input type="hidden" name="id_pemesanan_del" id="id_pemesanan_del" class="form-control mt-3" value="">
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="resetData();">Cancel</button>
                <button type="button" class="btn btn-outline-danger" onclick="deleteData()">Delete</button>
              </div>
            </div>
          </div>
        </div>

        <div class="row justify-content-center pesan">
          <div class="col-lg-8">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title fw-bold">Form Pemesanan</h5>
                <form action="" id="form-data">
                  <div class="row mb-3">
                    <label for="jenis" class="col-sm-3 col-form-label">Jenis Kendaraan &nbsp;:</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="jenis_kendaraan" name="jenis_kendaraan">
                    </div>
                  </div>
                  <div class="row mb-3">
                    <label for="tipe" class="col-sm-3 col-form-label">Tipe Kendaraan &nbsp;:</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="tipe" name="tipe">
                    </div>
                  </div>
                  <div class="row mb-3">
                    <label for="jenis" class="col-sm-3 col-form-label">Gambar Kendaraan &nbsp;:</label>
                    <div class="col-sm-9">
                      <input type="file" class="form-control" id="img" name="img">
                    </div>
                  </div>
                  <div class="row mb-3">
                    <div class="mt-4">
                      <div class="image-container">
                        <img src="" alt="" id="imgkndraan">
                      </div>

                    </div>
                  </div>
                  <div class="row mb-3">
                    <label for="bbm" class="col-sm-3 col-form-label">Konsumsi BBM &nbsp;:</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="bbm" name="bbm">
                    </div>
                  </div>
                  <div class="row mb-3">
                    <label for="jdwl_service" class="col-sm-3 col-form-label">Jadwal Service &nbsp;:</label>
                    <div class="col-sm-9">
                      <input type="text" id="jdwl_service" name="jdwl_service" class="form-control" placeholder="Pilih tanggal" readonly >
                      <span style="margin-left: 5px; color: gray;">(Klik untuk memilih tanggal)</span>
                    </div>
                  </div>
                  <div class="row mb-3">
                    <label for="datetime" class="col-sm-3 col-form-label">Waktu Pemesanan &nbsp;:</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="datetime" name="datetime" readonly disabled>
                    </div>
                  </div>
                  <div class="row mb-3">
                    <label for="driver" class="col-sm-3 col-form-label">Driver &nbsp;:</label>
                    <div class="col-sm-9">
                      <select class="form-control" data-width="100%" id="driver" name="driver"></select>
                    </div>
                  </div>
                  <div class="row mb-3">
                    <label for="pihak_penyetuju" class="col-sm-3 col-form-label">Pihak Yang Menyetujui&nbsp;:</label>
                    <div class="col-sm-9">
                      <select class="form-control" data-width="100%" id="pihak_penyetuju" name="pihak_penyetuju"></select>
                    </div>
                  </div>
                  <div class="row mb-3">
                    <label for="catatan" class="col-sm-3 col-form-label">Catatan&nbsp;:</label>
                    <div class="col-sm-9">
                      <textarea class="form-control" id="catatan" name="catatan" placeholder="Optional"></textarea>
                    </div>
                  </div>
                  <div class="row mb-3">
                    <div class="col-sm-9 offset-sm-3 text-end">
                      <button type="button" class="btn btn-secondary cancel">Tutup</button>
                      <button type="button" class="btn btn-primary" id="btn-save" onclick="saveData();">Tambah</button>
                      <input type="hidden" name="act" id="act" value="create">
                      <input type="hidden" name="id_pemesanan" id="id_pemesanan">
                    </div>
                  </div>

                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php include 'footer.php'; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script type="text/javascript">
  function initializeFlatpickr() {
    flatpickr("#jdwl_service", {
      dateFormat: "Y-m-d"
    });
  }
  $(document).ready(function() {
    initializeFlatpickr();
    $('.pesan').hide();
    $('.tble').show();
    $('#imgkndraan').hide();

    var levelName = getCookie("level");

    if (levelName !== "admin") {
      window.location.href="dashboard.php";
    }

    function getCookie(name) {
      var cookies = document.cookie.split(';');
      for (var i = 0; i < cookies.length; i++) {
        var cookie = cookies[i].trim();
        if (cookie.indexOf(name + '=') === 0) {
          return cookie.substring(name.length + 1, cookie.length);
        }
      }
      return "";
    }

    $('.add').on("click", function() {
      $('.pesan').fadeIn(400);
      $('.tble').hide(600);
      resetData();
    })

    $('.cancel').on("click", function() {
      $('.pesan').hide(600);
      $('.tble').fadeIn(400);
      resetData();
    })
  });
  flatpickr("#datetime", {
    enableTime: true,
    dateFormat: "Y-m-d H:i",
    defaultDate: new Date(),
  });
  flatpickr("#jdwl_service", {
    enableTime: false,
    dateFormat: "Y-m-d",
  });
  setTable();

  function setTable() {
    var table = $('#sample_4').DataTable({
      "processing": true,
      "serverSide": true,
      'serverMethod': 'post',
      "ajax": {
        url: '../server/svr_pesan.php?act=readTable'
      },
      "columns": [{
        "data": "no"
      }, {
        "data": "jenis_kendaraan",
        "width": "30%",
      }, {
        "data": "tipe",
        "width": "10%",
        "class": "text-center",
      }, {
        "data": "driver"
      }, {
       "data": "status",
       "render": function(data, type, row) {
        if (data == 0) {
          return '<span class="badge bg-warning text-white">Tunggu Persetujuan Dari Pool</span>';
        } else if (data == 1) {
          return '<span class="badge bg-success text-white">Pengajuan Sudah Disetujui</span>';
        }else if (data == 2) {
          return '<span class="badge bg-danger text-white">Pengajuan Ditolak</span>';
        } else {
          return data;
        }
      }
    }],
      "rowId": function(a) {
        return 'id_' + a.no;
      },
      "columnDefs": [{
        "targets": 5,
        "data": null,
        "render": function(id, index, data, type, row) {
          var id = "'" + data.id_pemesanan + "'";
          var btn = '<div style="display:flex;gap:5px;justify-content:center;">';
                        // if (data.edit == 1) {
          btn += '<button type="button" onclick="detailData(' + "'update'" + ',' + id +
          ')" class="btn  btn-warning p-action">';
          btn += '<i class="ti ti-edit"></i>';
          btn += '</button>';
          btn += '<button type="button" onclick="detailData(' + "'view'" + ',' + id +
          ')" class="btn btn-success btn-outline p-action">';
          btn += '<i class="ti ti-file"></i>';
          btn += '</button>';
                        // }
                        // if (data.del == 1) {
          btn += '<button type="button" onclick="setIdDel(\'' + data.id_pemesanan + '\')" class="btn btn-outline-danger p-action" data-bs-toggle="modal" data-bs-target="#deleteModal">';
          btn += '<i class="ti ti-trash" ></i>';
          btn += '</button>';
                        // }
                        // if(data.menu_id == null){
                        //     window.location.replace("403/ind");
                        // }
          btn += '</div>';

          return btn;
        }
      }],
      responsive: true,
      "order": [
        [2, 'desc']
        ],
      "lengthMenu": [
        [5, 10, 15, 20, 100],
      [5, 10, 15, 20, 100] // change per page values here
      ],
      "pageLength": 10,
      "destroy": true
    });

  // Remove the default search input
    $('#sample_4_filter').empty();

  // Add a custom input element for the 4-digit search
    $('#sample_4_filter').append('<input class="form-control" style="margin-bottom:10px" type="search" id="customSearchInput" placeholder="Search (2 digits)">');

    var customSearchValue = '';

  // Initialize the custom search
    $('#customSearchInput').on('input', function() {
      customSearchValue = this.value.trim();
      if (customSearchValue.length >= 2) {
      // console.log('work');
        table.search(customSearchValue).draw();
      }
      if (customSearchValue.length === 0) {
      // console.log('staging');
        table.search('').draw();
        return false;
      }
    });

  // handle datatable custom tools
    $('#sample_3_tools > li > a.tool-action').on('click', function() {
      var action = $(this).attr('data-action');
      table.button(action).trigger();
    });
  }
  function setIdDel(id_pemesanan_del) {
    $('#id_pemesanan_del').val(id_pemesanan_del);
  }
  function detailData(tipe, id_pemesanan) {
    $('.pesan').fadeIn(400);
    $('.tble').hide(600);

    $('#act').val(tipe);
    if (tipe == 'view') {
      $('#btn-save').hide();
    } else {
      $('#btn-save').show();
      $('#btn-save').text('edit');

    }
    $.ajax({
      url: '../server/svr_pesan.php',
      method: 'POST',
      data: {
        act: "read",
        id_pemesanan : id_pemesanan
      },

      complete: function(res) {
        var data = res.responseJSON.data[0];
        if (data != "") {
          $('#id_pemesanan').val(res.responseJSON.xid_pemesanan);
          $('#jenis_kendaraan').val(data.jenis_kendaraan);
          $('#tipe').val(data.tipe);
          $('#bbm').val(data.bbm);
          $('#jdwl_service').val(data.jdwl_service);
          $('#driver').val(data.driver);
          $('#pihak_penyetuju').val(data.pihak_penyetuju);
          $('#catatan').val(data.notes);
          $('#datetime').val(data.waktu_pemesanan);
          $('#imgkndraan').show();
          $('#imgkndraan').attr("src", 'assets/img_kendaraan/' + data.img_kendaraan);
        }
      }
    });

  }
  function deleteData() {
    var id_pemesanan_del = $('#id_pemesanan_del').val();
    var reason = $('#reason').val();
    if ($('#reason').val() == "") {
      Swal.fire({
        title: 'Alasan Harus Di isi!',
        icon: 'warning'
      });
      return false;
    }

    $.ajax({
      url: "../server/svr_pesan.php",
      method: 'post',
      data: {
        act: 'delete',
        id_pemesanan_del: id_pemesanan_del,
        reason: reason
      },
      success: function(result) {
        if (result == 'success') {
          setTable();
          Swal.fire({
            position: 'top',
            icon: 'success',
            title: 'Data Berhasil Dihapus',
            showConfirmButton: false,
            timer: 1500
          }).then(() => {
            $('#deleteModal').modal('hide');
          });
          $('#reason').val('');
          $('#id_pemesanan_del').val('');
        } else {
          Swal.fire({
            title: 'Oopss..',
            text: 'An error occurred' + result,
            icon: 'error'
          })
        }
      }
    });
  }

  getSelectOption();

  function getSelectOption() {
    $.ajax({
      url: "../server/svr_pesan.php?act=pool",
      method: 'GET',
      success: function (res) {
        var akun = '<option value="" selected >Pilih Pihak Penyetuju</option>';
        for (var i = 0; i < res.data.length; i++) {
          akun += '<option value="' + res.data[i].id_user + '">' + res.data[i]
          .full_name +
          '</option>';
        }

        $('#pihak_penyetuju').append(akun);

      }
    });
    $.ajax({
      url: "../server/svr_pesan.php?act=driver",
      method: 'GET',
      success: function (res) {
        var akun = '<option value="" selected >Pilih Driver</option>';
        for (var i = 0; i < res.data.length; i++) {
          akun += '<option value="' + res.data[i].id_user + '">' + res.data[i]
          .full_name +
          '</option>';
        }

        $('#driver').append(akun);

      }
    });
  }

  function saveData() {
    var act = $('#act').val();
    var jenis_kendaraan = $('#jenis_kendaraan').val();
    if ($('#jenis_kendaraan').val() == "") {
      Swal.fire({
        title: 'Jenis Kendaraan Tidak Boleh Kosong',
        icon: 'warning'
      });
      return false;
    }
    var tipe = $('#tipe').val();
    if ($('#tipe').val() == "") {
      Swal.fire({
        title: 'Tipe Kendaraan Tidak Boleh Kosong',
        icon: 'warning'
      });
      return false;
    }
    var bbm = $('#bbm').val();
    if ($('#bbm').val() == "") {
      Swal.fire({
        title: 'Konsumsi BBM Tidak Boleh Kosong',
        icon: 'warning'
      });
      return false;
    }
    var jdwl_service = $('#jdwl_service').val();
    if ($('#jdwl_service').val() == "") {
      Swal.fire({
        title: 'Jadwal Service Harus Terisi',
        icon: 'warning'
      });
      return false;
    }
    var datetime = $('#datetime').val();
    var driver = $('#driver').val();
    if ($('#driver').val() == "") {
      Swal.fire({
        title: 'Driver Harus Terisi',
        icon: 'warning'
      });
      return false;
    }
    var pihak_penyetuju = $('#pihak_penyetuju').val();
    if ($('#pihak_penyetuju').val() == "") {
      Swal.fire({
        title: 'Harus Memilih Pihak Yang Menyetujui',
        icon: 'warning'
      });
      return false;
    }
    var catatan = $('#catatan').val();
    var img = $('#img').val();

    var form = $('#form-data')[0];
    var formData = new FormData(form);
    formData.append('act',act);
    formData.append('img',img);
    formData.append('jenis_kendaraan',jenis_kendaraan);
    formData.append('tipe',tipe);
    formData.append('jdwl_service',jdwl_service);
    formData.append('bbm',bbm);
    formData.append('datetime',datetime);
    formData.append('driver',driver);
    formData.append('pihak_penyetuju',pihak_penyetuju);
    formData.append('catatan',catatan);

    $.ajax({
      url: "../server/svr_pesan.php",
      data: formData,
      processData: false,
      contentType: false,
      type: 'POST',
      success: function(res) {
        if (res == "success") {
          Swal.fire({
            position: "top",
            icon: "success",
            title: "Berhasil",
            showConfirmButton: false,
            timer: 1500
          });

          $('.pesan').hide();
          $('.tble').fadeIn(400);
          setTable();
          resetData();
          $('.btn-action').attr('disabled', false);
          $('.btn-action').text('Simpan');
        } else {
         Swal.fire({
          title: 'Oopss..',
          text: 'An error occurred' + res,
          icon: 'error'
        })
       }
     }
   });

  }


  function resetData(){
    $('#jenis_kendaraan').val('');
    $('#tipe').val('');
    $('#bbm').val('');
    $('#jdwl_service').val('');
    $('#driver').val('');
    $('#pihak_penyetuju').val('');
    $('#catatan').val('');
  $('#img').val(''); // Reset value of the file input
  $('#imgkndraan').attr('src', '').hide(); // Remove the image source and hide the image
}

</script>
</body>
</html>
