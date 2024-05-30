<?php
header('Content-type: application/json');
$target = 3;
require_once "controllers/class.CtrlGlobal.php";
$objCtrl = new CtrlGlobal();
require_once "controllers/class.CtrlNumber.php";
$objNumber = new CtrlNumber();
$act = ($_GET['act'] == "")?$_POST['act']:$_GET['act'];
$act = $objCtrl->filterParams($act);
$jenis_kendaraan = $objCtrl->filterParams($_POST['jenis_kendaraan']);
$tipe = $objCtrl->filterParams($_POST['tipe']);
$jdwl_service = $objCtrl->filterParams($_POST['jdwl_service']);
$bbm = $objCtrl->filterParams($_POST['bbm']);
$datetime = $objCtrl->filterParams($_POST['datetime']);
$driver = $objCtrl->filterParams($_POST['driver']);
$pihak_penyetuju = $objCtrl->filterParams($_POST['pihak_penyetuju']);
$catatan = $objCtrl->filterParams($_POST['catatan']);
$reason = $objCtrl->filterParams($_POST['reason']);

$id_pemesanan = $objCtrl->filterParams($_POST['id_pemesanan']);
$id_pemesanan = $objCtrl->decode($id_pemesanan);
$id_pemesanan_del = $objCtrl->filterParams($_POST['id_pemesanan_del']);
$id_pemesanan_del = $objCtrl->decode($id_pemesanan_del);


$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value
$searchQuery = "";

switch ($act) {
   case 'create':
   $id_pemesanan = $objNumber->getNoMaster('id_pemesanan','pemesanan','PMSN');
   $insert = $objCtrl->insert('pemesanan',array(
      'id_pemesanan' => $id_pemesanan,
      'jenis_kendaraan' => $jenis_kendaraan,
      'tipe' => $tipe,
      'jdwl_service' => $jdwl_service,
      'bbm' => $bbm,
      'driver' => $driver,
      'pihak_penyetuju' => $pihak_penyetuju,
      'waktu_pemesanan' => $datetime,
      'notes' => $catatan,
      'created_at' =>date('Y-m-d'),
      'status' =>0,
   ));
   $uploadDir = __DIR__ . '/../system/assets/img_kendaraan/';
   if ($_FILES['img']['name'] != '') {
      if ($_FILES['img']['size'] < 10000000) {
               //10MB
         $imageinfo = getimagesize($_FILES['img']['tmp_name']);

         if ($imageinfo['mime'] == 'image/gif' || $imageinfo['mime'] == 'image/jpeg' || $imageinfo['mime'] == 'image/png' || $imageinfo['mime'] == 'image/jpg') {
            if ($imageinfo['mime'] == 'image/jpg' || $imageinfo['mime'] == 'image/jpeg') {
               $uploadedfile = $_FILES['img']['tmp_name'];
               $src          = imagecreatefromjpeg($uploadedfile);
            } else if ($imageinfo['mime'] == 'image/png') {
               $uploadedfile = $_FILES['img']['tmp_name'];
               $src          = imagecreatefrompng($uploadedfile);
            } else {
               $uploadedfile = $_FILES['img']['tmp_name'];
               $src          = imagecreatefromgif($uploadedfile);
            }
            list($width, $height) = getimagesize($uploadedfile);

            $newwidth  = 174;
            $newheight = ($height / $width) * $newwidth;
            $tmp       = imagecreatetruecolor($newwidth, $newheight);
            imagecopyresampled($tmp, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);


            $name_id = explode('/', $id_pemesanan);
            $name_id = $name_id[0] . $name_id[1] . $name_id[2] . $name_id[3] . $name_id[4] ;
            $filename = $name_id .".jpeg";
                  // echo $filename;
                  // echo ($uploadDir .$filename);
                  // unlink($uploadDir . $filename);

            $pro = imagejpeg($tmp, $uploadDir . $filename, 100);

            imagedestroy($src);
            imagedestroy($tmp);
                  //Toni : ini ketika update nama tabel nya masih salah

            if ($pro == true) {
               $msg = "File is valid, and uploaded.\n";
               $objCtrl->update('pemesanan', array(
                  'img_kendaraan' => $filename,
               ), array('id_pemesanan' => $id_pemesanan));
            } else {
               $msg = "File uploading failed.\n" . $gambar;
               echo json_encode($msg);
               exit();
            }
         } else {

            $msg = "Sorry, only .gif, .jpg and .png ! \n";
            echo json_encode($msg);
            exit;
         }
      } else {
         $msg = "Sorry, File must be under 10 Mb ! \n";
         echo json_encode($msg);
         exit();
      }
   }
   echo json_encode($insert);
   break;
   case 'read':
   $sql = "SELECT * FROM pemesanan WHERE id_pemesanan = '".$id_pemesanan."'";
   $row['data'] = $objCtrl->GetGlobalFilter($sql);
   $row['xid_pemesanan'] = $objCtrl->encode($objCtrl->GetGlobalFilter($sql)[0]['id_pemesanan']);
   echo json_encode($row);
   break;
   case 'update':
   // $sql = "SELECT * FROM m_user WHERE id_user = '".$id_user_del."'";
   // $row = $objCtrl->GetGlobalFilter($sql);
   // foreach ($row as $item) {
   //    $objCtrl->insert('m_user_edit',array(
   //       'id_user' => $item['id_user'],
   //       'full_name' => $item['full_name'],
   //       'username' => $item['username'],
   //       'password' => $item['password'],
   //       'level' => $item['level'],
   //       'type' =>'update',
   //       'user_act' =>$_COOKIE['id_user'],
   //       'log_time' =>date('Y-m-d H:i:s'),

   //    ));
   // }
   $uploadDir = __DIR__ . '/../system/assets/img_kendaraan/';
   if ($_FILES['img']['name'] != '') {
      if ($_FILES['img']['size'] < 10000000) {
               //10MB
         $imageinfo = getimagesize($_FILES['img']['tmp_name']);

         if ($imageinfo['mime'] == 'image/gif' || $imageinfo['mime'] == 'image/jpeg' || $imageinfo['mime'] == 'image/png' || $imageinfo['mime'] == 'image/jpg') {
            if ($imageinfo['mime'] == 'image/jpg' || $imageinfo['mime'] == 'image/jpeg') {
               $uploadedfile = $_FILES['img']['tmp_name'];
               $src          = imagecreatefromjpeg($uploadedfile);
            } else if ($imageinfo['mime'] == 'image/png') {
               $uploadedfile = $_FILES['img']['tmp_name'];
               $src          = imagecreatefrompng($uploadedfile);
            } else {
               $uploadedfile = $_FILES['img']['tmp_name'];
               $src          = imagecreatefromgif($uploadedfile);
            }
            list($width, $height) = getimagesize($uploadedfile);

            $newwidth  = 174;
            $newheight = ($height / $width) * $newwidth;
            $tmp       = imagecreatetruecolor($newwidth, $newheight);
            imagecopyresampled($tmp, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);


            $name_id = explode('/', $id_pemesanan);
            $name_id = $name_id[0] . $name_id[1] . $name_id[2] . $name_id[3] . $name_id[4] ;
            $filename = $name_id .".jpeg";
                  // echo $filename;
                  // echo ($uploadDir .$filename);
                  // unlink($uploadDir . $filename);

            $pro = imagejpeg($tmp, $uploadDir . $filename, 100);

            imagedestroy($src);
            imagedestroy($tmp);
                  //Toni : ini ketika update nama tabel nya masih salah

            if ($pro == true) {
               $msg = "File is valid, and uploaded.\n";
               $objCtrl->update('pemesanan', array(
                  'img_kendaraan' => $filename,
               ), array('id_pemesanan' => $id_pemesanan));
            } else {
               $msg = "File uploading failed.\n" . $gambar;
               echo json_encode($msg);
               exit();
            }
         } else {

            $msg = "Sorry, only .gif, .jpg and .png ! \n";
            echo json_encode($msg);
            exit;
         }
      } else {
         $msg = "Sorry, File must be under 10 Mb ! \n";
         echo json_encode($msg);
         exit();
      }
   }
   $update = $objCtrl->update('pemesanan',array(
      'jenis_kendaraan' => $jenis_kendaraan,
      'tipe' => $tipe,
      'jdwl_service' => $jdwl_service,
      'bbm' => $bbm,
      'driver' => $driver,
      'pihak_penyetuju' => $pihak_penyetuju,
      'notes' => $catatan,
   ),array( 'id_pemesanan' => $id_pemesanan));
   echo json_encode($update);
   break;
   case 'delete':
   // $sql = "SELECT * FROM m_user WHERE id_user = '".$id_user_del."'";
   // $row = $objCtrl->GetGlobalFilter($sql);
   // foreach ($row as $item) {
   //    $objCtrl->insert('m_user_edit',array(
   //       'id_user' => $item['id_user'],
   //       'full_name' => $item['full_name'],
   //       'username' => $item['username'],
   //       'password' => $item['password'],
   //       'level' => $item['level'],
   //       'reason' => $reason,
   //       'type' =>'delete',
   //       'user_act' =>$_COOKIE['id_user'],
   //       'log_time' =>date('Y-m-d H:i:s'),

   //    ));
   // }

   $delete = $objCtrl->delete('pemesanan',array(
      'id_pemesanan' => $id_pemesanan_del
   ));
   echo json_encode($delete);
   break;
   case 'readTable':
   if($searchValue != ''){
     $searchQuery.= " and (driver like '%".$searchValue."%' OR jenis_kendaraan like '%".$searchValue."%' OR tipe like '%".$searchValue."%') ";
  }
  $sqltot = "SELECT count(id_pemesanan) as name FROM pemesanan where 1=1";
  $sqltot.= $searchQuery;
  $rowtot = $objCtrl->getName($sqltot);

  $sql = "SELECT u.*, l.full_name as nama_driver , p.full_name as nama_penyetuju FROM pemesanan u JOIN m_user l ON u.driver = l.id_user JOIN m_user p ON u.pihak_penyetuju = p.id_user WHERE 1=1";
  $sql.= $searchQuery;
  if($columnName != "no" && $columnName != ""){
   $sql.=" order by ".$columnName." ".$columnSortOrder;
}
$sql.=" limit ".$row.",".$rowperpage;
$no = $row+1;
$array_row = [];
$row = $objCtrl->GetGlobalFilter($sql);
foreach ($row as $item) {
 $data[] = array( 
   'id_pemesanan' => $objCtrl->encode($item['id_pemesanan']),
   'driver' => $item['nama_driver'],
   'jenis_kendaraan' => $item['jenis_kendaraan'],
   'tipe' => $item['tipe'],
   'status' => $item['status'],
   'no' => $no,   
);
 $no++;
}
$data = [
   'sql' => $sql,
   "draw" => intval($draw),
   "iTotalRecords" => $rowtot,
   "iTotalDisplayRecords" => $rowtot,
   'data' => $data,
];
echo json_encode($data);
break;
case 'test':
echo "Arif Ragil";
break;
case 'pool':
$sql = "SELECT * FROM m_user WHERE level = 'pool'";
$row['data'] = $objCtrl->GetGlobalFilter($sql);
echo json_encode($row);
break;
case 'driver':
$sql = "SELECT * FROM m_user WHERE level = 'driver'";
$row['data'] = $objCtrl->GetGlobalFilter($sql);
echo json_encode($row);
break;

default:
      // code...
break;
}
?>