<?php
header('Content-type: application/json');
$target = 3;
require_once "controllers/class.CtrlGlobal.php";
$objCtrl = new CtrlGlobal();
require_once "controllers/class.CtrlNumber.php";
$objNumber = new CtrlNumber();
$act = ($_GET['act'] == "")?$_POST['act']:$_GET['act'];
$act = $objCtrl->filterParams($act);
$email = $objCtrl->filterParams($_POST['email']);
$fullname = $objCtrl->filterParams($_POST['fullname']);
$level = $objCtrl->filterParams($_POST['level']);
$password = $objCtrl->filterParams($_POST['password']);
$reason = $objCtrl->filterParams($_POST['reason']);

$id_user = $objCtrl->filterParams($_POST['id_user']);
$id_user = $objCtrl->decode($id_user);
$id_user_del = $objCtrl->filterParams($_POST['id_user_del']);
$id_user_del = $objCtrl->decode($id_user_del);


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
   $id_user = $objNumber->getNoMaster('id_user','m_user','U');
   $insert = $objCtrl->insert('m_user',array(
      'id_user' => $id_user,
      'email' => $email,
      'full_name' => $fullname,
      'password' => $objCtrl->encode($password),
      'level' => $level,
      'status' => 'not_activate',
   ));
   if ($level == 'driver') {
      $uploadDir = __DIR__ . '/../system/assets/images/profile_driver/';
   }else{
      $uploadDir = __DIR__ . '/../system/assets/images/profile/';
   }
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
   echo json_encode($insert);
   break;
   case 'read':
   $sql = "SELECT id_user,full_name,level,profile,email FROM m_user WHERE id_user = '".$id_user."'";
   $row['data'] = $objCtrl->GetGlobalFilter($sql);
   $row['xid_user'] = $objCtrl->encode($objCtrl->GetGlobalFilter($sql)[0]['id_user']);
   echo json_encode($row);
   break;
   case 'update':
   $sql = "SELECT * FROM m_user WHERE id_user = '".$id_user_del."'";
   $row = $objCtrl->GetGlobalFilter($sql);

   if ($level == 'driver') {
     $uploadDir = __DIR__ . '/../system/assets/images/profile_driver/';
  } else {
     $uploadDir = __DIR__ . '/../system/assets/images/profile/';
  }

    // Periksa apakah email yang dipost telah digunakan
  $checkEmailSQL = "SELECT id_user FROM m_user WHERE email = '$email' AND id_user != '$id_user'";
  $existingUser = $objCtrl->GetGlobalFilter($checkEmailSQL);

  if (!empty($existingUser)) {
     $msg = "Email telah digunakan oleh pengguna lain.\n";
     echo json_encode($msg);
     exit;
  }

    // Periksa apakah file telah diunggah
  if (!empty($_FILES['photo']['name'])) {
        // Periksa ukuran file
        if ($_FILES['photo']['size'] < 10000000) { // 10MB
            // Dapatkan informasi gambar
         $imageinfo = getimagesize($_FILES['photo']['tmp_name']);

            // Periksa apakah file merupakan tipe gambar yang valid
         if (in_array($imageinfo['mime'], ['image/gif', 'image/jpeg', 'image/png'])) {
                // Buat sumber gambar berdasarkan tipe file
          $uploadedfile = $_FILES['photo']['tmp_name'];
          if ($imageinfo['mime'] == 'image/jpeg') {
           $src = imagecreatefromjpeg($uploadedfile);
        } elseif ($imageinfo['mime'] == 'image/png') {
           $src = imagecreatefrompng($uploadedfile);
        } else {
           $src = imagecreatefromgif($uploadedfile);
        }

        list($width, $height) = getimagesize($uploadedfile);

                // Tetapkan dimensi baru
        $newwidth = 174;
        $newheight = ($height / $width) * $newwidth;

                // Buat sumber gambar sementara baru
        $tmp = imagecreatetruecolor($newwidth, $newheight);
        imagecopyresampled($tmp, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

                // Hasilkan nama file yang unik
        $name_id = explode('/', $id_user);
        $name_id = $name_id[0] . $name_id[1] . $name_id[2] . $name_id[3] . $name_id[4];
        $filename = $name_id . ".jpeg";

                // Hapus file gambar lama
        $oldImagePath = $uploadDir . $filename;
        if (file_exists($oldImagePath)) {
           unlink($oldImagePath);
        }

                // Simpan gambar baru
        $pro = imagejpeg($tmp, $uploadDir . $filename, 100);

                // Hancurkan sumber daya gambar
        imagedestroy($src);
        imagedestroy($tmp);

                // Perbarui database jika gambar berhasil diunggah
        if ($pro) {
           $msg = "File valid dan berhasil diunggah.\n";
           $objCtrl->update('m_user', ['profile' => $filename], ['id_user' => $id_user]);
        } else {
           $msg = "Gagal mengunggah file.\n";
           echo json_encode($msg);
           exit();
        }
     } else {
       $msg = "Maaf, hanya file .gif, .jpg, dan .png yang diizinkan!\n";
       echo json_encode($msg);
       exit;
    }
 } else {
   $msg = "Maaf, file harus kurang dari 10 MB!\n";
   echo json_encode($msg);
   exit();
}
}
$update = $objCtrl->update('m_user', [
  'full_name' => $fullname,
  'email' => $email,
  'level' => $level,
], ['id_user' => $id_user]);

if ($password != "") {
  $objCtrl->update('m_user', [
   'password' => $objCtrl->encode($password),
], ['id_user' => $id_user]);
}

    // foreach ($row as $item) {
    //    $objCtrl->insert('m_user_edit',array(
    //       'id_user' => $item['id_user'],
    //       'npwpd' => $item['npwpd'],
    //       'nama_wajib_pajak' => $item['nama_wajib_pajak'],
    //       'pemilik' => $item['pemilik'],
    //       'id_kecamatan' => $item['id_kecamatan'],
    //       'type' =>'update',
    //       'user_act' =>$_COOKIE['id_user'],
    //       'log_time' =>date('Y-m-d H:i:s'),

    //    ));
    // }
echo json_encode($update);

break;
case 'delete':
$sql = "SELECT * FROM m_user WHERE id_user = '".$id_user_del."'";
$row = $objCtrl->GetGlobalFilter($sql);
      // foreach ($row as $item) {
      //    $objCtrl->insert('m_user_edit',array(
      //       'id_user' => $item['id_user'],
      //       'npwpd' => $item['npwpd'],
      //       'nama_wajib_pajak' => $item['nama_wajib_pajak'],
      //       'pemilik' => $item['pemilik'],
      //       'id_kecamatan' => $item['id_kecamatan'],
      //       'reason' => $reason,
      //       'type' =>'delete',
      //       'user_act' =>$_COOKIE['id_user'],
      //       'log_time' =>date('Y-m-d H:i:s'),

      //    ));
      // }

$delete = $objCtrl->delete('m_user',array(
   'id_user' => $id_user_del
));
echo json_encode($delete);
break;
case 'readTable':
if($searchValue != ''){
 $searchQuery.= " and (email like '%".$searchValue."%') ";
}
$sqltot = "SELECT * FROM m_user where level != 'admin' and 1=1";
$sqltot.= $searchQuery;
$rowtot = $objCtrl->GetGlobalFilter($sqltot);

$sqlslrh = "SELECT count(id_user) as name from m_user where level != 'admin' and 1=1";
$rowslrh = $objCtrl->getName($sqlslrh);

$sql = "SELECT * FROM m_user where level !='admin'";
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
     'id_user' => $objCtrl->encode($item['id_user']),
     'full_name' => $item['full_name'],
     'level' => $item['level'],
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
case 'test':
echo "Arif Ragil";
break;
default:
      // code...
break;
}
?>