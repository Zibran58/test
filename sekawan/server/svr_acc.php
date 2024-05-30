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
    case 'readTable':
    if($searchValue != ''){
       $searchQuery.= " and (jenis_kendaraan like '%".$searchValue."%') ";
   }
   $sqltot = "SELECT * FROM pemesanan where status = '0' and 1=1 and pihak_penyetuju = '".$id_user."'";
   $sqltot.= $searchQuery;
   $rowtot = $objCtrl->GetGlobalFilter($sqltot);

   $sqlslrh = "SELECT count(id_pemesanan) as name 
   FROM pemesanan 
   WHERE pihak_penyetuju = '".$id_user."' 
   AND status = '0'";

   $rowslrh = $objCtrl->getName($sqlslrh);

   $sql = "SELECT a.id_pemesanan,a.jenis_kendaraan,a.tipe,b.full_name as driver,b.id_user FROM pemesanan a JOIN m_user b ON a.driver = b.id_user where a.status = '0' and a.pihak_penyetuju = '".$id_user."'";
   $sql.= $searchQuery;
   if($columnName != "no" && $columnName != ""){
     $sql.=" order by ".$columnName." ".$columnSortOrder;
 }
 $sql.=" limit ".$row.",".$rowperpage;
 $no = $row+1;
 $array_row = [];
        //Access File
 $row = $objCtrl->GetGlobalFilter($sql);
        // $access = explode('#', $objCtrl->getAccessFile('108'));
 $data = array();
 foreach ($row as $item) {
  $data[] = array( 
   'id_pemesanan' => $objCtrl->encode($item['id_pemesanan']),
   'id_user' => $objCtrl->encode($item['id_user']),
   'driver' => $item['driver'],
   'jenis_kendaraan' => $item['jenis_kendaraan'],
   'tipe' => $item['tipe'],
   'no' => $no,
                // 'view' => $access[0],
                // 'edit' => $access[1],
                // 'del' => $access[2],
                // 'add' => $access[3],
                // 'menu_id' => $access[8],    
);
  $no++;
}
$data = [
   'sql' => $sql,
   "draw" => intval($draw),
   "iTotalRecords" => $rowslrh,
   "iTotalDisplayRecords" => $rowslrh,
   'data' => $data,
];
echo json_encode($data);
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

default:
        // code...
break;
}
?>
