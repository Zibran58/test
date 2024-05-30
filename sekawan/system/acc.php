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

<body>
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
  data-sidebar-position="fixed" data-header-position="fixed">
  <?php 
  include 'left_panel.php'
  ?>
  <div class="body-wrapper">
    <!--  Header Start -->
    <?php 
    include 'main.php'
    ?>
    <!--  Header End -->
    <div class="container-fluid">
      <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h3>
          <a href="#">Pool</a>
        </h3>
      </div>
      <div class="card shadow mb-4 panel-table">
        <div class="card-body">
          <div class="table-responsive">
            <table id="sample_4" class="table table-striped mb-4 text-center" style="width:100%">
              <thead>
                <tr>
                  <th width="10%" class="text-center">No</th>
                  <th width="10%" class="text-center">Jenis Kendaraan</th>
                  <th width="10%" class="text-center">Tipe Kendaraan</th>
                  <th width="16%" class="text-center">Driver</th>
                  <th width="16%" class="text-center">Aksi</th>
                </tr>
              </thead>
            </table>
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
                <input type="text" name="id_pemesanan" id="id_pemesanan" class="form-control mt-3" value="">
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-outline-danger" onclick="cncl()">Delete</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php 
    include 'footer.php'
    ?>
  </body>
  <script type="text/javascript">
    $(document).ready(function() {
      var levelName = getCookie("level");

      if (levelName !== "pool") {
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
    })
    setTable();
    function setTable() {
      var table = $('#sample_4').dataTable({
        "processing": true,
        "serverSide": true,
        'serverMethod': 'post',
        "ajax": {
          url: '../server/svr_acc.php?act=readTable'
        },

        "columns": [{
          "data": "no",
        }, {
          "data": "jenis_kendaraan",
        }, {
          "data": "tipe",
        }, {
          "data": "driver",
        }],
        rowId: function (a) {
          return 'id_' + a.no;
        },
        "columnDefs": [{
          "targets": 4,
          "data": null,
          "render": function (id, index, data, type, row) {
            var id = data.id_pemesanan;
            var id_user = data.id_user;
            var btn = '<div style="display:flex;gap:5px;justify-content:center;">';

            btn += '<button type="button" onclick="acc(\'' + id + '\', \'' + id_user + '\')" class="btn  btn-primary">';
            btn += '<i class="ti ti-check"></i>';
            btn += '</button>';
            btn += '<button type="button" onclick="setId(\'' + id + '\')" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">';
            btn += '<i class="ti ti-x"></i>';
            btn += '</button>';

            btn += '</div>';
            return btn;
          }

        }],

        buttons: [{
          extend: 'print',
          className: 'btn dark btn-outline'
        },
        {
          extend: 'copy',
          className: 'btn red btn-outline'
        },
        {
          extend: 'pdf',
          className: 'btn green btn-outline'
        },
        {
          extend: 'excel',
          className: 'btn yellow btn-outline '
        },
        {
          extend: 'csv',
          className: 'btn purple btn-outline '
        },
        {
          extend: 'colvis',
          className: 'btn dark btn-outline',
          text: 'Kolom'
        }],
        responsive: false,
        "order": [
          [2, 'desc']
          ],

        "lengthMenu": [
          [5, 10, 15, 20, 100],
            [5, 10, 15, 20, 100] // change per page values here
            ],
        // set the initial value
        "pageLength": 10,
        "destroy": true
      });

    // Remove the default search input
      $('#sample_4_filter').empty();

    // Add a custom input element for the 4-digit search
      $('#sample_4_filter').append('<input class="form-control" style="margin-bottom:10px" type="search" id="customSearchInput" placeholder="Cari (2 digit)">');

      var customSearchValue = '';

      $('#customSearchInput').on('input', function () {
        customSearchValue = this.value.trim();
        if (customSearchValue.length >= 2) {
          table.api().search(customSearchValue).draw();
        }
        if (customSearchValue.length === 0) {
          table.api().search('').draw();
          return false;
        }
      });

      $('#sample_3_tools > li > a.tool-action').on('click', function () {
        var action = $(this).attr('data-action');
        table.DataTable().button(action).trigger();
      });
    }
    function setId(id){
      $('#id_pemesanan').val(id);
    }
    function acc(id,id_user) {
      $.ajax({
        url: '../server/svr_acc.php',
        method: 'POST',
        data: {
          act: "update",
          id_pemesanan: id,
          id_user: id_user,
        },
        success: function(result) {
          if (result == 'success') {
            setTable();
            Swal.fire({
              position: 'top',
              icon: 'success',
              title: 'Pemesanan Telah Disetujui',
              showConfirmButton: false,
              timer: 1500
            });
            setTable(); 
          } else {
            Swal.fire({
              title: 'Oops..',
              text: 'An error occurred in ' + result,
              icon: 'error'
            });
          }
        }

      });
    }
    function cncl() {
      var id_pemesanan = $('#id_pemesanan').val();
      var reason = $('#reason').val();
      $.ajax({
        url: '../server/svr_acc.php',
        method: 'POST',
        data: {
          act: "cncl",
          id_pemesanan: id_pemesanan,
          reason : reason
        },
        success: function(result) {
          if (result == 'success') {
            setTable();
            Swal.fire({
              position: 'top',
              icon: 'success',
              title: 'Pengajuan Pemesanan Ditolak',
              showConfirmButton: false,
              timer: 1500
            }).then(() => {
          // Menutup modal setelah menampilkan pesan sukses
              $('#deleteModal').modal('hide');
            });
            setTable(); 
          } else {
            Swal.fire({
              title: 'Oops..',
              text: 'An error occurred in ' + result,
              icon: 'error'
            });
          }
        }

      });
    }
  </script>

  </html>
