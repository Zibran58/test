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
      <div class="row">
        <div class="col-md-4">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Laporan Bulanan</h5>
              <form>
                <div class="mb-3">
                  <label for="tahunSelect" class="form-label">Tahun:</label>
                  <select class="form-select" id="tahunSelect" name="tahunSelect">
                    <!-- Add year options here -->
                    <?php
                    for ($i = date('Y'); $i <= date('Y') + 5; $i++) {
                      echo '<option value="' . $i . '" ' . ($i == $selectedYear ? 'selected' : '') . '>' . $i . '</option>';
                    }
                    ?>
                  </select>
                </div>

                <!-- Select for Month -->
                <div class="mb-3">
                  <label for="bulanSelect" class="form-label">Bulan:</label>
                  <select class="form-select" id="bulanSelect" name="bulanSelect">
                    <!-- Add month options here -->
                    <?php
                    for ($m = 1; $m <= 12; $m++) {
                      $month = str_pad($m, 2, '0', STR_PAD_LEFT);
                      echo '<option value="' . $month . '" ' . ($month == $selectedMonth ? 'selected' : '') . '>' . date('F', mktime(0, 0, 0, $m, 1)) . '</option>';
                    }
                    ?>
                  </select>
                </div>
                <button type="button" class="btn btn-primary btn-user btn-block" onclick="generateMonthlyReports();">
                  Generate Report
                </button>
              </form>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Laporan Tahunan</h5>
              <form>
                <div class="mb-3">
                  <label for="tahunSelect" class="form-label">Tahun:</label>
                  <select class="form-select" id="tahunSelect2" name="tahunSelect2">
                    <!-- Add year options here -->
                    <?php
                    for ($i = date('Y'); $i <= date('Y') + 5; $i++) {
                      echo '<option value="' . $i . '" ' . ($i == $selectedYear ? 'selected' : '') . '>' . $i . '</option>';
                    }
                    ?>
                  </select>
                </div>
                <button type="button" class="btn btn-primary btn-user btn-block" onclick="generateAnnualReports();">
                  Generate Report
                </button>
              </form>
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
    })

    function generateMonthlyReports() {
      var year = $('#tahunSelect').val();
      var month = $('#bulanSelect').val();

      $.ajax({
        url: '../server/svr_dashboard.php',
        method: 'POST',
        dataType: 'json',
        data: {
          act: 'reportMonth',
          tahunSelect: year,
          bulanSelect: month
        },
        success: function(response) {
          if (!response || !response.data || response.data.length === 0) {
            Swal.fire({
              title: 'Tidak Ada Data Pada Bulan Atau Tahun Yang Di Pilih',
              showClass: {
                popup: 'animate__animated animate__fadeInDown'
              },
              hideClass: {
                popup: 'animate__animated animate__fadeOutUp'
              }
            });
            return false;
          }

          var dataFromServer = response.data;

          if (dataFromServer.length === 0) {
            alert("No data available for this month.");
            return;
          }

          var excelData = [];
          var headerRow = Object.keys(dataFromServer[0]);
          excelData.push(headerRow);

          dataFromServer.forEach(function(item) {
            var rowData = Object.values(item);
                // Mengubah nilai status menjadi teks yang sesuai
            rowData[headerRow.indexOf('status')] = getStatusText(rowData[headerRow.indexOf('status')]);
            excelData.push(rowData);
          });

          var today = new Date();
          var fileName = 'monthly_report_' + year + '_' + (month < 10 ? '0' + month : month) + '.xlsx';

          var ws = XLSX.utils.aoa_to_sheet(excelData);

            // Set style for header row (row 1)
          var range = XLSX.utils.decode_range(ws['!ref']);
          for (var C = range.s.c; C <= range.e.c; ++C) {
            var cell_address = { c: C, r: 0 };
            var cell_ref = XLSX.utils.encode_cell(cell_address);
            if (!ws[cell_ref]) ws[cell_ref] = { t: "s", v: headerRow[C] };
            ws[cell_ref].s = {
              fill: { fgColor: { rgb: "0000FF" } },
              font: { color: { rgb: "FFFFFF" }, bold: true }
            };
          }

          var wb = XLSX.utils.book_new();
          XLSX.utils.book_append_sheet(wb, ws, 'Monthly Report');
          XLSX.writeFile(wb, fileName);
        },
        error: function(error) {
          alert("Failed to fetch data from the server. Check your connection and try again.");
          console.error(error);
        }
      });
    }

// Fungsi untuk mengubah nilai status menjadi teks yang sesuai
    function getStatusText(status) {
      switch (status) {
      case '0':
        return 'Tunggu Persetujuan Dari Pool';
      case '1':
        return 'Pengajuan Sudah Disetujui';
      case '2':
        return 'Pengajuan Ditolak';
      default:
        return 'Status tidak valid';
      }
    }

    function generateAnnualReports() {
      var year = $('#tahunSelect2').val();

      $.ajax({
        url: '../server/svr_dashboard.php',
        method: 'POST',
        dataType: 'json',
        data: {
          act: 'reportYears',
          tahunSelect: year,
        },
        success: function(response) {
          if (!response || !response.data || response.data.length === 0) {
            Swal.fire({
              title: 'Tidak Ada Data Pada Tahun Yang Dipilih',
              showClass: {
                popup: 'animate__animated animate__fadeInDown'
              },
              hideClass: {
                popup: 'animate__animated animate__fadeOutUp'
              }
            });
            return false;
          }

          var dataFromServer = response.data;

          if (dataFromServer.length === 0) {
            alert("No data available for this year.");
            return;
          }

          var excelData = [];
          var headerRow = Object.keys(dataFromServer[0]);
          excelData.push(headerRow);

          dataFromServer.forEach(function(item) {
            var rowData = Object.values(item);
                // Mengubah nilai status menjadi teks yang sesuai
            rowData[headerRow.indexOf('status')] = getStatusText(rowData[headerRow.indexOf('status')]);
            excelData.push(rowData);
          });

          var fileName = 'annual_report_' + year + '.xlsx';

          var ws = XLSX.utils.aoa_to_sheet(excelData);

            // Set style for header row (row 1)
          var range = XLSX.utils.decode_range(ws['!ref']);
          for (var C = range.s.c; C <= range.e.c; ++C) {
            var cell_address = { c: C, r: 0 };
            var cell_ref = XLSX.utils.encode_cell(cell_address);
            if (!ws[cell_ref]) ws[cell_ref] = { t: "s", v: headerRow[C] };
            ws[cell_ref].s = {
              fill: { fgColor: { rgb: "0000FF" } },
              font: { color: { rgb: "FFFFFF" }, bold: true }
            };
          }

          var wb = XLSX.utils.book_new();
          XLSX.utils.book_append_sheet(wb, ws, 'Annual Report');
          XLSX.writeFile(wb, fileName);
        },
        error: function(error) {
          alert("Failed to fetch data from the server. Check your connection and try again.");
          console.error(error);
        }
      });
    }



  </script>

  </html>
