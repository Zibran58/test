<?php
ini_set('display_errors', 1);
header('Content-type: application/json');
$target = 3;
require_once "controllers/class.CtrlGlobal.php";
$objCtrl = new CtrlGlobal();
require_once "controllers/class.CtrlNumber.php";
$objNumber = new CtrlNumber();
$act = ($_GET['act'] == "") ? $_POST['act'] : $_GET['act'];
$act = $objCtrl->filterParams($act);
$fullname = $objCtrl->filterParams($_POST['fullname']);
$alamat = $objCtrl->filterParams($_POST['alamat']);
$email = $objCtrl->filterParams($_POST['email']);
$password = $objCtrl->filterParams($_POST['password']);
$id_kecamatan = $objCtrl->filterParams($_POST['id_kecamatan']);

$id_user = $objCtrl->filterParams($_COOKIE['id_user']);
$id_user = $objCtrl->decode($id_user);
// $id_user = $objCtrl->filterParams($_POST['id_user']);
// echo $id_user;
// $id_user = $objCtrl->decode($id_user);


$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value
$searchQuery = "";
switch ($act) {
   case 'read':
   $sql = "SELECT id_user,email,full_name,profile,level FROM m_user WHERE id_user = '" . $id_user . "'";
   $row['data'] = $objCtrl->GetGlobalFilter($sql);
   $row['xid_user'] =$objCtrl->encode($objCtrl->GetGlobalFilter($sql)[0]['id_user']);
   echo json_encode($row);
   break;
   case 'update':
   $sql = "SELECT * FROM m_user WHERE id_user = '".$id_user."'";
   $row = $objCtrl->GetGlobalFilter($sql);

   $uploadDir = __DIR__ . '/../system/assets/images/profile/';
   if ($_FILES['photo']['name'] != '') {
      if ($_FILES['photo']['size'] < 10000000) {
            //10MB
         $imageinfo = getimagesize($_FILES['photo']['tmp_name']);

         if ($imageinfo['mime'] == 'image/gif' || $imageinfo['mime'] == 'image/jpeg' || $imageinfo['mime'] == 'image/png' || $imageinfo['mime'] == 'image/jpg') {
            if ($imageinfo['mime'] == 'image/jpg' || $imageinfo['mime'] == 'image/jpeg') {
               $uploadedfile = $_FILES['photo']['tmp_name'];
               $src          = imagecreatefromjpeg($uploadedfile);
            } else if ($imageinfo['mime'] == 'image/png') {
               $uploadedfile = $_FILES['photo']['tmp_name'];
               $src          = imagecreatefrompng($uploadedfile);
            } else {
               $uploadedfile = $_FILES['photo']['tmp_name'];
               $src          = imagecreatefromgif($uploadedfile);
            }
            list($width, $height) = getimagesize($uploadedfile);

            $newwidth  = 174;
            $newheight = ($height / $width) * $newwidth;
            $tmp       = imagecreatetruecolor($newwidth, $newheight);
            imagecopyresampled($tmp, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);


            $name_id = explode('/', $id_user);
            $name_id = $name_id[0] . $name_id[1] . $name_id[2] . $name_id[3] . $name_id[4] ;
            $filename = $name_id . $date .".jpeg";
               // echo $filename;
               // echo ($uploadDir .$filename);
               // unlink($uploadDir . $filename);

            $pro = imagejpeg($tmp, $uploadDir . $filename, 100);

            imagedestroy($src);
            imagedestroy($tmp);
               //Toni : ini ketika update nama tabel nya masih salah

            if ($pro == true) {
               $msg = "File is valid, and uploaded.\n";
               $objCtrl->update('m_user', array(
                  'profile' => $filename,
               ), array('id_user' => $id_user));
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
   $update = $objCtrl->update('m_user', array(
      'full_name' => $fullname,
      'email' => $email,
   ), array('id_user' => $id_user));
   if ($password != "") {
      $objCtrl->update('m_user', array(
         'password' => $objCtrl->encode($password),
      ), array('id_user' => $id_user));
   }
   $objCtrl->setCookies('photo',$filename);
      // $objCtrl->setCookies('full_name',$fullname);
   echo json_encode($update);
   break;
   default:
      // code...
   break;
}
