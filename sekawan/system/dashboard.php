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
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
  data-sidebar-position="fixed" data-header-position="fixed">
  <!-- Sidebar Start -->
  <?php 
  include 'left_panel.php'
  ?>
  <!--  Sidebar End -->
  <!--  Main wrapper -->
  <div class="body-wrapper">
    <!--  Header Start -->
    <?php 
    include 'main.php'
    ?>
    <!--  Header End -->
    <div class="container-fluid w-100 admin">
      <!-- 4 Cards for Totals -->
      <div class="row">
        <div class="col-md-3">
          <div class="card shadow mb-4">
            <div class="card-body">
              <h5 class="card-title fw-semibold">Total Users</h5>
              <h3  class="text-primary"><i class="ti ti-user"></i><span id="totalUsers" style="margin-left: 10px;">0</span></h3>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card shadow mb-4">
            <div class="card-body">
              <h5 class="card-title fw-semibold">Total Admins</h5>
              <h3  class="text-success"><i class="ti ti-user"></i><span id="totalAdmins" style="margin-left: 10px;">0</span></h3>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card shadow mb-4">
            <div class="card-body">
              <h5 class="card-title fw-semibold">Total Drivers</h5>
              <h3  class="text-warning"><i class="ti ti-user"></i><span id="totalDrivers" style="margin-left: 10px;">0</span></h3>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card shadow mb-4">
            <div class="card-body">
              <h5 class="card-title fw-semibold">Total Pools</h5>
              <h3  class="text-info"><i class="ti ti-user"></i><span id="totalPools" style="margin-left: 10px;">0</span></h3>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-12 d-flex align-items-strech">
          <div class="card w-100">
            <div class="card-body">
              <div class="d-sm-flex d-block align-items-center justify-content-between mb-9">
              </div>
              <div id="chart"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="container-fluid w-100 pool">
      <div class="row">
        <div class="col-md-6">
          <div class="card shadow mb-4">
            <div class="card-body">
              <h5 class="card-title fw-semibold">Driver Sudah Di Acc</h5>
              <h3 class="text-primary"><i class="ti ti-user-check"></i><span id="userActive" style="margin-left: 10px;">0</span></h3>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card shadow mb-4">
            <div class="card-body">
              <h5 class="card-title fw-semibold">Driver Belum Di Acc</h5>
              <h3 class="text-success"><i class="ti ti-user-exclamation"></i><span id="userNa" style="margin-left: 10px;">0</span></h3>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="container-fluid w-100 driver">
      <div class="row">
      </div>
    </div>

    <?php 
    include 'footer.php'
    ?>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script type="text/javascript">

  $(document).ready(function() {
    var levelName = getCookie("level");

    if (levelName === "admin") {
      $(".admin").show();
      $(".pool").hide();
      $(".driver").hide();
    } else if (levelName === "pool") {
      $(".admin").hide();
      $(".pool").show();
      $(".driver").hide();
    } else if (levelName === "driver") {
      $(".admin").hide();
      $(".pool").hide();
      $(".driver").show();
    } else {
        // Jika level tidak diketahui, sembunyikan semuanya atau tampilkan pesan kesalahan
      $(".admin").hide();
      $(".pool").hide();
      $(".driver").hide();
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
  });


  function animateValue($element, start, end, duration) {
    let startTimestamp = null;
    const step = function(timestamp) {
      if (!startTimestamp) startTimestamp = timestamp;
      const progress = Math.min((timestamp - startTimestamp) / duration, 1);
      $element.text(Math.floor(progress * (end - start) + start));
      if (progress < 1) {
        window.requestAnimationFrame(step);
      }
    };
    window.requestAnimationFrame(step);
  }

  function fetchVehicleData() {
    $.ajax({
      url: '../server/svr_dashboard.php',
      method: 'GET',
      data: { act: 'getVehicleData' },
      dataType: 'json',
      success: function(response) {
        console.log(response);
      if (response && response.length > 0) { // Periksa apakah respons tidak kosong dan memiliki elemen
        var vehicleData = response[0];
        var numVehicles = response.length; // Jumlah kendaraan
        var $driverContainer = $(".driver");

        // Hapus konten sebelumnya
        $driverContainer.empty();

        // Loop melalui setiap kendaraan
        response.forEach(function(vehicleData) {
          var $vehicleCard = $('<div class="col-lg-12">' +
            '<div class="card shadow mb-4 border-left-primary">' +
            '<div class="card-body">' +
            '<h5 class="card-title fw-bold mb-4" style="color: #4e73df;">Detail Kendaraan</h5>' +
            '<div class="row">' +
            '<div class="col-md-6">' +
            '<p class="mb-3"><i class="ti ti-car"></i> <strong> Jenis Kendaraan:</strong> <span>' + (vehicleData.jnis_kndraan || '') + '</span></p>' +
            '<p class="mb-3"><i class="ti ti-calendar"></i> <strong> Tipe:</strong> <span>' + (vehicleData.tipe || '') + '</span> </p>' +
            '<p class="mb-3"><i class="ti ti-gas-station"></i> <strong> Penggunaan BBM(L):</strong> <span>' + (vehicleData.bbm || '') + '</span> </p>' +
            '<p class="mb-3"><i class="ti ti-settings"></i> <strong> Jadwal Service:</strong> <span>' + (vehicleData.jdwl_service || '') + '</span> </p>' +
            '<p class="mb-3"><i class="ti ti-clock"></i> <strong> Waktu Pemesanan:</strong> <span>' + (vehicleData.waktu_pemesanan || '') + '</span> </p>' +
            '<p class="mb-3"><i class="ti ti-circle-check"></i> <strong> Pihak Penyetuju:</strong> <span>' + (vehicleData.pihak_penyetuju || '') + '</span> </p>' +
            '<p class="mb-3"><i class="ti ti-credit-card"></i> <strong> Status:</strong> <span>' + getStatusText(vehicleData.status_usr) + '</span> </p>' +
            (vehicleData.status_usr == 2 ? '<p class="mb-3"><i class="ti ti-message-report"></i><strong>Alasan Ditolak:</strong> <span>' + (vehicleData.reason_cncl || '') + '</span></p>' : '') + 
            '</div>' +
            '<div class="col-md-6">' +
            '<img src="' + (vehicleData.img_kendaraan ? 'assets/img_kendaraan/' + vehicleData.img_kendaraan : './assets/images/noimage.avif') + '" alt="Gambar Kendaraan" class="mb-3 img-fluid rounded" style="max-width: 100%; max-height: 200px;">' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>');

          $driverContainer.append($vehicleCard);
        });
      } else {
        console.error('Empty or invalid JSON response from the server.');
      }
    },
    error: function(xhr, status, error) {
      console.error('Error fetching data from the server:', error);
    }
  });
  }


  fetchVehicleData();

  function getStatusText(statusCode) {
    switch (parseInt(statusCode)) {
    case 0:
      return 'Tunggu Persetujuan Dari Pool';
    case 1:
      return 'Pengajuan Sudah Disetujui';
    case 2:
      return 'Pengajuan Ditolak';
    default:
      return 'Status tidak valid';
    }
  }


  function fetchTotals() {
    $.ajax({
      url: '../server/svr_dashboard.php',
      method: 'GET',
      data: { act: 'getTotals' },
      dataType: 'json',
      success: function (response) {
        if (response) {
          animateValue($('#totalUsers'), parseInt($('#totalUsers').text()), response.user, 1000);
          animateValue($('#totalAdmins'), parseInt($('#totalAdmins').text()), response.admin, 1000);
          animateValue($('#totalPools'), parseInt($('#totalPools').text()), response.pool, 1000);
          animateValue($('#totalDrivers'), parseInt($('#totalDrivers').text()), response.driver, 1000);
          animateValue($('#userActive'), parseInt($('#userActive').text()), response.active, 1000);
          animateValue($('#userNa'), parseInt($('#userNa').text()), response.not_active, 1000);
        } else {
          console.error('Empty or invalid JSON response from the server.');
        }
      },
      error: function (xhr, status, error) {
        console.error('Error fetching data from the server:', error);
      }
    });
  }


  function fetchDataAndRenderChart() {
    $.ajax({
      url: '../server/svr_dashboard.php',
      method: 'GET',
      data: { act: 'getLineChartData' },
      dataType: 'json',
      success: function (response) {
        if (response.chartData.length > 0) {
          var chart = new ApexCharts(document.querySelector("#chart"), {
            series: [{
              name: "Menunggu Konfirmasi Pool",
              data: response.chartData.map(item => item.status_1)
            }, {
              name: "Disetujui Oleh Pool",
              data: response.chartData.map(item => item.status_2)
            }, {
              name: "Ditolak Oleh Pool",
              data: response.chartData.map(item => item.status_3)
            }],
            chart: {
              type: 'area',
              height: 300,
              zoom: {
                enabled: false
              }
            },
            dataLabels: {
              enabled: false
            },
            stroke: {
              curve: 'smooth'
            },
            title: {
              text: 'Kendaraan',
              align: 'left'
            },
            subtitle: {
              text: 'Total Pemesanan Kendaraan: ' + response.subtitle,
              align: 'left'
            },
            labels: response.chartData.map(item => item.order_month),
            xaxis: {
              type: 'category',
            },
            yaxis: {
              opposite: true
            },
            legend: {
              horizontalAlign: 'left'
            }
          });

        // Render the chart
          chart.render();
        } else {
          console.error('Empty or invalid JSON response from the server.');
        }
      },
      error: function (xhr, status, error) {
        console.error('Error fetching data from the server:', error);
      }
    });

  // Fetch totals and update cards
    fetchTotals();
  }
  fetchDataAndRenderChart();
  function fetchAll(){
    fetchDataAndRenderChart();
    fetchVehicleData();
  }

// Fetch and render data every 5 minutes
  setInterval(fetchAll, 300000);


</script>

</html>
