<?php
header('Content-type: application/json');
$target = 3;
require_once "controllers/class.CtrlGlobal.php";
$objCtrl = new CtrlGlobal();
require_once "controllers/class.CtrlNumber.php";
$objNumber = new CtrlNumber();
$act = ($_GET['act'] == "") ? $_POST['act'] : $_GET['act'];
$act = $objCtrl->filterParams($act);
$id_user = $_COOKIE['id_user'];
$id_user = $objCtrl->decode($id_user);
$id_pemesanan = ($_GET['id_pemesanan'] == "") ? $_POST['id_pemesanan'] : $_GET['id_pemesanan'];
$id_pemesanan = $objCtrl->decode($id_pemesanan);

$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value
$searchQuery = "";

switch ($act) {
    case 'getLineChartData':
    $sqlLineChart = "SELECT 
    MONTHNAME(waktu_pemesanan) as order_month,
    COALESCE(COUNT(IF(status = 0, 1, NULL)), 0) as status_1,
    COALESCE(COUNT(IF(status = 1, 1, NULL)), 0) as status_2,
    COALESCE(COUNT(IF(status = 2, 1, NULL)), 0) as status_3,
    COUNT(id_pemesanan) as subtitle
    FROM pemesanan 
    GROUP BY MONTH(waktu_pemesanan)";
    
    $resultLineChart = $objCtrl->GetGlobalFilter($sqlLineChart);

    $lineChartData = [];
    $months = array(
        'January', 'February', 'March', 'April', 'May', 'June',
        'July', 'August', 'September', 'October', 'November', 'December'
    );

    // Initialize subtitle
    $subtitle = 0;

    foreach ($months as $month) {
        $lineChartData[] = [
            'order_month' => mb_substr($month, 0, 3, 'UTF-8'),
            'status_1' => 0,
            'status_2' => 0,
            'status_3' => 0,
        ];
    }

    foreach ($resultLineChart as $lineChartItem) {
        $monthIndex = intval(date('m', strtotime($lineChartItem['order_month']))) - 1;
        if ($monthIndex !== false) {
            $lineChartData[$monthIndex]['status_1'] = $lineChartItem['status_1'];
            $lineChartData[$monthIndex]['status_2'] = $lineChartItem['status_2'];
            $lineChartData[$monthIndex]['status_3'] = $lineChartItem['status_3'];
        }

        // Set the subtitle to the total count of orders
        $subtitle = $lineChartItem['subtitle'];
    }

    // Add subtitle to the response data
    $response = [
        'chartData' => $lineChartData,
        'subtitle' => $subtitle
    ];

    echo json_encode($response);
    break;

    case 'getTotals':
    $sql = "SELECT 
    COUNT(id_user) as totalUser,
    COALESCE(COUNT(IF(level = 'admin', 1, NULL)), 0) as totalAdmin,
    COALESCE(COUNT(IF(level = 'driver', 1, NULL)), 0) as totalDriver,
    COALESCE(COUNT(IF(level = 'pool', 1, NULL)), 0) as totalPool
    FROM m_user";

    $row = $objCtrl->GetGlobalFilter($sql);

    $sqlusr = "SELECT 
    COALESCE(COUNT(IF(status = 'activate', 1, NULL)), 0) as totalActive,
    COALESCE(COUNT(IF(status = 'not_activate', 1, NULL)), 0) as totalNa
    FROM m_user";
    $rowusr = $objCtrl->GetGlobalFilter($sqlusr);

    if ($row && $rowusr) {
        $response = [
            'user' => $row[0]['totalUser'],
            'admin' => $row[0]['totalAdmin'],
            'pool' => $row[0]['totalPool'],
            'driver' => $row[0]['totalDriver'],
            'active' => $rowusr[0]['totalActive'],
            'not_active' => $rowusr[0]['totalNa'],
        ];
    } else {
        $response = [
            'user' => 0,
            'admin' => 0,
            'pool' => 0,
            'driver' => 0,
            'active' => 0,
            'not_active' => 0,
        ];
    }
    echo json_encode($response);
    break;

    case 'getVehicleData':
    $id_user = $_COOKIE['id_user'];
    $id_user = $objCtrl->decode($id_user);
    
    // Query SQL untuk mengambil data pemesanan kendaraan oleh driver tertentu
    $sql = "SELECT a.*,b.full_name as penyetuju FROM pemesanan a JOIN m_user b on a.pihak_penyetuju = b.id_user WHERE driver = '".$id_user."'";
    
    // Ambil data dari database
    $response = $objCtrl->GetGlobalFilter($sql);
    
    // Pastikan data yang diambil adalah array asosiatif
    $formattedResponse = array();

    foreach ($response as $row) {
        $formattedResponse[] = array(
            'jnis_kndraan' =>$row['jenis_kendaraan'],
            'tipe' =>$row['tipe'],
            'bbm' =>$row['bbm'],
            'jdwl_service' =>$row['jdwl_service'],
            'waktu_pemesanan' =>$row['waktu_pemesanan'],
            'pihak_penyetuju' =>$row['penyetuju'],
            'status_usr' =>$row['status'],
            'img_kendaraan' =>$row['img_kendaraan'],
            'reason_cncl' =>$row['reason_cncl'],
        );
    }
    
    // Keluarkan respons dalam format JSON
    echo json_encode($formattedResponse);
    break;


    break;
    case 'update':
    $id_user = ($_GET['id_user'] == "") ? $_POST['id_user'] : $_GET['id_user'];
    $id_user = $objCtrl->decode($id_user);

    $objCtrl->update('m_user', array(
        'status' => 'activate',
    ), array('id_user' => $id_user));
    $update = $objCtrl->update('pemesanan', array(
        'status' => '1',
    ), array('id_pemesanan' => $id_pemesanan));
    echo json_encode($update);
    break;

    case 'cncl':
    $reason = ($_GET['reason'] == "") ? $_POST['reason'] : $_GET['reason'];
    $reason = $objCtrl->filterParams($reason);
    $update = $objCtrl->update('pemesanan', array(
        'status' => '2',
        'reason_cncl' => $reason,
    ), array('id_pemesanan' => $id_pemesanan));
    echo json_encode($update);
    break;

    case 'reportMonth':
    // Ambil tahun dan bulan dari data POST
    $tahun = isset($_POST['tahunSelect']) ? $_POST['tahunSelect'] : date('Y');
    $bulan = isset($_POST['bulanSelect']) ? $_POST['bulanSelect'] : date('m');

    // Pastikan nilai bulan dan tahun di-sanitasi sebelum digunakan dalam query
    $tahun = intval($tahun);
    $bulan = intval($bulan);

    // Query SQL yang sudah diperbaiki
    $sql = "SELECT a.id_pemesanan, a.jenis_kendaraan, a.tipe, a.bbm, a.jdwl_service, a.waktu_pemesanan, 
    a.driver, a.pihak_penyetuju, a.notes, a.status, a.reason_cncl, 
    b.full_name as driver, c.full_name as pihak_penyetuju 
    FROM pemesanan a
    JOIN m_user b on a.driver = b.id_user 
    JOIN m_user c on a.pihak_penyetuju = c.id_user 
    WHERE MONTH(a.waktu_pemesanan) = $bulan 
    AND YEAR(a.waktu_pemesanan) = $tahun 
    GROUP BY a.id_pemesanan, a.waktu_pemesanan";

    $row = $objCtrl->GetGlobalFilter($sql);

    echo json_encode(['data' => $row]);
    break;

    case 'reportYears':
    // Ambil tahun dan bulan dari data POST
    $tahun = isset($_POST['tahunSelect']) ? $_POST['tahunSelect'] : date('Y');

    // Pastikan nilai bulan dan tahun di-sanitasi sebelum digunakan dalam query
    $tahun = intval($tahun);
    $bulan = intval($bulan);

    // Query SQL yang sudah diperbaiki
    $sql = "SELECT a.id_pemesanan, a.jenis_kendaraan, a.tipe, a.bbm, a.jdwl_service, a.waktu_pemesanan, 
    a.driver, a.pihak_penyetuju, a.notes, a.status, a.reason_cncl, 
    b.full_name as driver, c.full_name as pihak_penyetuju 
    FROM pemesanan a
    JOIN m_user b on a.driver = b.id_user 
    JOIN m_user c on a.pihak_penyetuju = c.id_user 
    WHERE YEAR(a.waktu_pemesanan) = $tahun 
    GROUP BY a.id_pemesanan, a.waktu_pemesanan";

    $row = $objCtrl->GetGlobalFilter($sql);

    echo json_encode(['data' => $row]);
    break;


    case 'reportYears':
                // Ambil tahun dan bulan dari data POST
    $tahun = isset($_POST['tahunSelect']) ? $_POST['tahunSelect'] : date('Y');

    $sql = "SELECT a.id_order, GROUP_CONCAT(a.qty) AS qty, b.total_bayar, b.waktu_order, e.alamat, GROUP_CONCAT(d.nama_menu) AS nama_menu, e.full_name
    FROM c_detail_order a 
    LEFT JOIN m_order b ON a.id_order = b.id_order 
    LEFT JOIN m_daftar_menu d ON a.id_menu = d.id_menu
    LEFT JOIN m_user e ON b.id_user = e.id_user
    WHERE YEAR(b.waktu_order) = $tahun
    GROUP BY a.id_order, b.total_bayar, b.waktu_order";

    $row = $objCtrl->GetGlobalFilter($sql);

    echo json_encode(['data' => $row]);
    break;

    default:
        // code...
    break;
}
?>
