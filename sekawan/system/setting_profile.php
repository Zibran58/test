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
     <div class="card shadow mb-4 form-table">
      <div class="card-body">
        <h5 class="card-title fw-semibold">Profile</h5>
        <form action="" id="form-data">
          <div class="row">
            <div class=" mt-4 ">
              <span>Fullname</span>
              <input type="text" name="fullname" id="fullname"
              class="form-control form-control-user mt-3" value="">
            </div>
            <div class=" mt-4 ">
              <span>Email</span>
              <input type="email" name="email" id="email"
              class="form-control form-control-user mt-3" value="">
            </div>
            <div class=" mt-4 ">
              <span>Password</span>
              <input type="text" name="password" id="password"
              class="form-control form-control-user mt-3" value="" placeholder="Hanya Isi Jika Akan Diubah">
            </div>
            <div class=" mt-4">
              <span>Foto Profile</span>
              <input  type="file" name="photo" id="photo"
              class="form-control mt-3" value="">
            </div>
            <div class=" mt-4"></div>
            <div class=" mt-4">
              <div class="image-container">
                <img src="" alt="" id="imgprofile">
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="card-footer">
        <div class="row">
          <div class="" align="right">
            <input type="hidden" name="id_user" id="id_user">
            <input type="hidden" name="act" id="act" value="update">
            <button type="button" class="btn btn-primary" id="btn-save" onclick="saveData();">SIMPAN</button>
          </div>
        </div>
      </div>
    </div>
    <?php include 'footer.php'?>
  </div>
</div>
</div>
</div>
<script type="text/javascript">
  function saveData() {
    var act = $('#act').val();
    var fullname = $('#fullname').val();
    var email = $('#email').val();
    var alamat = $('#alamat').val();
    var password = $('#password').val();
    var photo = $('#photo').val();
    var id_user = $('#id_user').val();

    if (email == '') {
      Swal.fire(
        'Sorry!',
        'email is Required!',
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

    var form = $('#form-data')[0];
    var formData = new FormData(form);
    formData.append('act',act);
    formData.append('id_user',id_user);
    formData.append('fullname',fullname);
    formData.append('alamat',alamat);
    formData.append('photo',photo);
    formData.append('email',email);
    formData.append('password',password);

    $.ajax({
      url: "../server/svr_setting_profile.php",
      data: formData,
      processData: false,
      contentType: false,
      type: 'POST',
      success: function(res) {
        if (res == "success") {
          Swal.fire({
            position: 'top',
            icon: 'success',
            title: 'Data Berhasil Diubah',
            showConfirmButton: false,
            timer: 1500
          }).then((value)=>{
            window.location.href="dashboard.php";
          });
          $('#btn-save').attr('disabled', false);
          $('#btn-save').text('Simpan');
        } else {
          Swal.fire({
           title: 'Oopss..',
           text: res,
           icon: 'error'
         })
        }
      },
    });

  }
  detailData();
  function detailData(id_user) {
    $.ajax({
      url: '../server/svr_setting_profile.php',
      method: 'POST',
      data: {
        act: "read",
        id_user: id_user
      },
      complete: function (res) {
        var data = res.responseJSON.data[0];
        if (data != "") {
          $('#id_user').val(res.responseJSON.xid_user);
          $('#fullname').val(data.full_name);
          if (data.profile != null && data.profile != '') {
            if (data.level === 'driver') {
              $('#imgprofile').attr("src", 'assets/images/profile_driver/' + data.profile);
            } else {
              $('#imgprofile').attr("src", 'assets/images/profile/' + data.profile);
            }
          } else {
            $("#imgprofile").hide();
          }

          $('#email').val(data.email);
          $('#password').val("");
          
        }
      }
    });
  }

</script>
</body>
</html>