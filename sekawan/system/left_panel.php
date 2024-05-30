<aside class="left-sidebar">
  <!-- Sidebar scroll-->
  <div>
    <div class="brand-logo d-flex align-items-center justify-content-between">
      <a href="#" class="text-nowrap logo-img">
        <img src="./assets/images/logos/logo.png" width="180" alt="" />
      </a>
      <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
        <i class="ti ti-x fs-8"></i>
      </div>
    </div>
    <!-- Sidebar navigation-->
    <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
      <ul id="sidebarnav">
        <li class="nav-small-cap home">
          <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
          <span class="hide-menu">Home</span>
        </li>
        <li class="sidebar-item dashboard">
          <a class="sidebar-link" href="./dashboard.php" aria-expanded="false">
            <span>
              <i class="ti ti-layout-dashboard"></i>
            </span>
            <span class="hide-menu">Dashboard</span>
          </a>
        </li>
        <li class="nav-small-cap spn">
          <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
          <span class="hide-menu">Menu</span>
        </li>
        <li class="sidebar-item pesan_lft">
          <a class="sidebar-link" href="pesan.php" aria-expanded="false">
            <span>
              <i class="ti ti-article"></i>
            </span>
            <span class="hide-menu">Pemesanan</span>
          </a>
        </li>
        <li class="sidebar-item kurir">
          <a class="sidebar-link" href="acc.php" aria-expanded="false">
            <span>
              <i class="ti ti-scooter"></i>
            </span>
            <span class="hide-menu">Persetujuan</span>
          </a>
        </li>
        <li class="nav-small-cap manage">
          <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
          <span class="hide-menu">Manage</span>
        </li>
        <li class="sidebar-item pegawai">
          <a class="sidebar-link" href="pegawai.php" aria-expanded="false">
            <span>
              <i class="ti ti-users"></i>
            </span>
            <span class="hide-menu">Pegawai</span>
          </a>
        </li>
        <li class="sidebar-item laporan">
          <li class="sidebar-item laporan">
            <a class="sidebar-link" href="generate_report.php" aria-expanded="false">
              <span>
                <i class="ti ti-report"></i>
              </span>
              <span class="hide-menu">Laporan</span>
            </a>
          </li>
        </ul>
      </nav>
      <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
  </aside>
  <script src="./assets/libs/jquery/dist/jquery.min.js"></script>
  <script type="text/javascript">
    $(document).ready(function() {
      var levelName = getCookie("level");

      if (levelName === "driver") {
        $(".home, .manage, .pegawai, .addmenu, .laporan, .kurir, .koki,.spn,.pesan_lft").hide();
      }else if (levelName === "pool") {
        $(".manage,.pegawai,.laporan,.pesan,.pesan_lft").hide();
      }else if (levelName === "admin") {
        $(".kurir").hide();
        $(".pesan_lft").show();
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
  </script>