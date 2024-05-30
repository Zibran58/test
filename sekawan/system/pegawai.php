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
          <a href="#">Pegawai</a>
        </h3>
      </div>
      <div class="card shadow mb-4 form-table">
        <div class="card-body">
          <form action="" id="form-data">
            <div class="row">
              <div class="mt-4 ">
                <span>Email</span>
                <input type="email" name="email" id="email"
                class="form-control form-control-user mt-3" value="">
              </div>
              <div class="mt-4 ">
                <span>Fullname</span>
                <input type="text" name="fullname" id="fullname"
                class="form-control form-control-user mt-3" value="">
              </div>
              <div class="mt-4 ">
                <span>Password</span>
                <input type="text" name="password" id="password"
                class="form-control form-control-user mt-3" value="" placeholder="Hanya Isi Jika Akan Diubah">
              </div>
              <div class="mt-4">
                <span for="level">Level</span>
                <select class="form-control form-control-user select2 mt-3" id="level" name="level">
                  <option value="" selected>Select</option>
                  <option value="admin">Admin</option>
                  <option value="pool">Pool</option>
                  <option value="driver">Driver</option>
                </select>
              </div>
              <div class="mt-4"></div>
              <div class="mt-4">
                <span>Foto Profile</span>
                <input  type="file" name="photo" id="photo"
                class="form-control mt-3" value="">
              </div>
              <div class="mt-4"></div>
              <div class="mt-4">
                <div class="image-container">
                  <img src="" alt="" id="imgprofile">
                </div>

              </div>
            </div>
          </form>
        </div>
        <div class="card-footer">
          <div class="row">
            <div class="col-md-12" align="right">
              <input type="hidden" name="id_user" id="id_user">
              <input type="hidden" name="act" id="act" value="create">
              <button type="button" class="btn btn-secondary cencel" data-dismiss="modal">Cancel</button>
              <button type="button" class="btn btn-primary" id="btn-save" onclick="saveData();">SIMPAN</button>
            </div>

          </div>
        </div>
      </div>
      <div class="card shadow mb-4 panel-table">
        <div class="card-body">
          <button class="btn btn-primary blue add mb-3" onclick="$('#act').val('create');"> <i class="ti ti-plus"></i>Tambah</button>
          <div class="table-responsive">
            <!-- <div class="portlet-body "> -->
              <table id="sample_4" class="table table-striped mb-4 text-center" style="width:100%">
                <thead>
                  <tr>
                    <th width="2%" class="text-center">No</th>
                    <th width="39%" class="text-center">Fullname</th>
                    <th width="39%" class="text-center">Level</th>
                    <th width="20%" class="text-center">Action</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
        <?php include 'footer.php' ?>
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
  </div>
</div>
</body>
<script type="text/javascript">
  $(document).ready(function() {
    $('.form-table').hide();

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
      $('.btn-action').val('save');
      $('.form-table').fadeIn(400);
      $('.panel-table').hide(600);
      $('.add').hide(600);
      resetData();
    })

    $('.cencel').on("click", function() {
      $('.form-table').hide(600);
      $('.panel-table').fadeIn(400);
      $('.add').fadeIn(600);
      $('#btn-save').attr('disabled', false);
      $('#btn-save').text('SIMPAN');
      resetData();
    })
  })
  setTable();
  function setTable() {
    var table = $('#sample_4').dataTable({
      "processing": true,
      "serverSide": true,
      'serverMethod': 'post',
      "ajax": {
        url: '../server/svr_pegawai.php?act=readTable'
      },

      "columns": [{
        "data": "no",
        "width": "5%"
      }, {
        "data": "full_name",
        "width": "5%"
      }, {
        "data": "level",
        "width": "5%"
      }],
      rowId: function (a) {
        return 'id_' + a.no;
      },
      "columnDefs": [{
        "targets": 3,
        "data": null,
        "render": function(id, index, data, type, row) {
          var id = "'" + data.id_user + "'";
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
      }, ],


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
        text: 'Columns'
      }],
      responsive: true,
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
    $('#sample_4_filter').append('<input class="form-control" style="margin-bottom:10px" type="search" id="customSearchInput" placeholder="Search (2 digits)">');

    var customSearchValue = '';

    // Initialize the custom search
    $('#customSearchInput').on('input', function () {
      customSearchValue = this.value.trim();
      if (customSearchValue.length >= 2) {
            // console.log('work');
        table.api().search(customSearchValue).draw();
      }
      if (customSearchValue.length === 0) {
            // console.log('staging');
        table.api().search('').draw();
        return false;
      }
    });

    // handle datatable custom tools
    $('#sample_3_tools > li > a.tool-action').on('click', function () {
      var action = $(this).attr('data-action');
      table.DataTable().button(action).trigger();
    });
  }
  function setIdDel(id_user_del) {
    $('#id_user_del').val(id_user_del);
  }

  function deleteData() {
    var id_user_del = $('#id_user_del').val();
    var reason = $('#reason').val();
    if ($('#reason').val() == "") {
      Swal.fire({
        title: 'Reason is Required!',
        icon: 'warning'
      });
      return false;
    }

    $.ajax({
      url: "../server/svr_daftar_meja.php",
      method: 'post',
      data: {
        act: 'delete',
        id_user_del: id_user_del,
        reason: reason
      },
      success: function(result) {
        if (result == 'success') {
          setTable();
          Swal.fire({
            position: 'top',
            icon: 'success',
            title: 'Data Successfully Deleted',
            showConfirmButton: false,
            timer: 1500
          })
          $('#reason').val('');
          $('#id_user_del').val('');
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

  function detailData(tipe, id_user) {
    $('.form-table').fadeIn(400);
    $('.panel-table').hide(600);
    $('.add').hide(600);

    $('#act').val(tipe);
    if (tipe == 'view') {
      $('#btn-save').hide();
    } else {
      $('#btn-save').show();
      $('#btn-save').text('edit');
    }
    $.ajax({
      url: '../server/svr_pegawai.php',
      method: 'POST',
      data: {
        act: "read",
        id_user : id_user
      },

      complete: function(res) {
        var data = res.responseJSON.data[0];
        if (data != "") {
          $('#id_user').val(res.responseJSON.xid_user);
          $('#fullname').val(data.full_name);
          $('#email').val(data.email);
          $('#level').val(data.level);
          $('#password').val("");
          if (data.level === "driver") {
            $('#imgprofile').attr("src", 'assets/images/profile_driver/' + data.profile);
          }else {
            $('#imgprofile').attr("src", 'assets/images/profile/' + data.profile);
          }
        }
      }
    });

  }



  function saveData() {
    var act = $('#act').val();
    var fullname = $('#fullname').val();
    var email = $('#email').val();
    var password = $('#password').val();
    var level = $('#level').val();
    var id_user = $('#id_user').val();
    var photo = $('#photo').val();

    if (email == '') {
      Swal.fire(
        'Sorry!',
        'Email is Required!',
        'warning'
        )
      return false;
    }
    if (fullname == '') {
      Swal.fire(
        'Sorry!',
        'Fullname is Required!',
        'warning'
        )
      return false;
    }
    if (level == '') {
      Swal.fire(
        'Sorry!',
        'level is Required!',
        'warning'
        )
      return false;
    }


    var form = $('#form-data')[0];
    var formData = new FormData(form);
    formData.append('act', act);
    formData.append('fullname', fullname);
    formData.append('email', email);
    formData.append('password', password);
    formData.append('level', level);
    formData.append('id_user', id_user);
    formData.append('photo', photo);
    $.ajax({
      url: "../server/svr_pegawai.php",
      data: formData,
      processData: false,
      contentType: false,
      type: 'POST',
      success: function(res) {
        if (res == "success") {
          Swal.fire({
            position: 'top',
            icon: 'success',
            title: 'Employee Successfully Added',
            showConfirmButton: false,
            timer: 1500
          })
          $('.add').show();
          $('.form-table').hide();
          $('.panel-table').fadeIn(400);
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
          $('.btn-action').attr('disabled', false);
          $('.btn-action').text('Simpan');
        }
      }
    });

  }


  function resetData() {
    $('#fullname').val('');
    $('#email').val('');
    $('#photo').val('');
    $('#level').val('');
    $('#imgprofile').attr('src', 'assets/images/noimage.avif');
    $('#password').val("");
  }
</script>

</html>