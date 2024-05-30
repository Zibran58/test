<?php
error_reporting(0);
header('Content-type: application/json');
$target = 3;
require_once "controllers/class.CtrlGlobal.php";
$objCtrl = new CtrlGlobal();
require_once "controllers/class.CtrlNumber.php";
$objNumber = new CtrlNumber();
session_start();
if ($_GET['act'] == "") {
  $act = $_POST['act'];
} else {
  $act = $_GET['act'];
}
$fullname = ($_GET['fullname'] == "")?$_POST['fullname']:$_GET['fullname'];
$fullname = $objCtrl->filterParams($fullname);
$email = ($_GET['email'] == "")?$_POST['email']:$_GET['email'];
$email = $objCtrl->filterParams($email);
$password = ($_GET['password'] == "")?$_POST['password']:$_GET['password'];
$password = $objCtrl->filterParams($password);
$full_name_or_email = ($_GET['full_name_or_email'] == "")?$_POST['full_name_or_email']:$_GET['full_name_or_email'];
$full_name_or_email = $objCtrl->filterParams($full_name_or_email);

switch ($act) {

    case 'login':
    if (isset($_POST['full_name_or_email'])) {
        $full_name_or_email = mysqli_real_escape_string($objCtrl->getConnection(), $_POST['full_name_or_email']);

        // Modifikasi query untuk memeriksa status user
        $sql = "SELECT *
        FROM m_user  
        WHERE full_name = '" . $full_name_or_email . "' OR email = '" . $full_name_or_email . "'";
        $row = $objCtrl->GetGlobalFilter($sql);

        if (sizeof($row) == 0) {
            $msg = "Username Tidak Tersedia!";
        } else {
            foreach ($row as $item) {
                if ($item['status'] != 'activate') {
                    $msg = "Akun belum di acc oleh pool!";
                } else {
                    if ($objCtrl->encode($_POST['password']) == $item['password']) {
                        $objCtrl->setCookies('id_user', $objCtrl->encode($item['id_user']));
                        if (empty(trim($item['username']))) {
                            $objCtrl->setCookies('username', $item['full_name']);
                        } else {
                            $objCtrl->setCookies('username', $item['username']);
                        }
                        $objCtrl->setCookies('level', $item['level']);
                        $objCtrl->setCookies('photo', $item['profile']);
                        $objCtrl->setCookies('email', $item['email']);

                        $msg = "success";
                    } else {
                        $msg = "Password Kurang Tepat!";
                    }
                }
            }
        }
    }


    echo json_encode($msg);
    break;


    case 'password':
    $username = mysqli_real_escape_string($objCtrl->getConnection(), $_POST['username']);
    $sql = "SELECT password as name FROM m_user WHERE username = '".$username."'";
    $rows = $objCtrl->getName($sql);
    $row['data'] = $objCtrl->decode($rows);
    echo json_encode($row['data']);

    break;

    case 'register':
    $emailvrf = "SELECT email FROM m_user WHERE email = '" . $email . "'";
    $row = $objCtrl->GetGlobalFilter($emailvrf);
    if (sizeof($row) == 0) {
        $id_user = $objNumber->getNoMaster('id_user', 'm_user', 'U');
        $msg = $objCtrl->insert('m_user', array(
            'id_user' => $id_user,
            'full_name' => $fullname,
            'email' => $email,
            'password' => $objCtrl->encode($password),
            'level' => 'driver',
            'status' => 'not_activate',
        ));
        $uploadDir = __DIR__ . '/../system/assets/images/profile_driver/';
        if ($_FILES['profile-pic']['name'] != '') {
            if ($_FILES['profile-pic']['size'] < 10000000) {
                //10MB
                $imageinfo = getimagesize($_FILES['profile-pic']['tmp_name']);

                if ($imageinfo['mime'] == 'image/gif' || $imageinfo['mime'] == 'image/jpeg' || $imageinfo['mime'] == 'image/png' || $imageinfo['mime'] == 'image/jpg') {
                    if ($imageinfo['mime'] == 'image/jpg' || $imageinfo['mime'] == 'image/jpeg') {
                        $uploadedfile = $_FILES['profile-pic']['tmp_name'];
                        $src = imagecreatefromjpeg($uploadedfile);
                    } else if ($imageinfo['mime'] == 'image/png') {
                        $uploadedfile = $_FILES['profile-pic']['tmp_name'];
                        $src = imagecreatefrompng($uploadedfile);
                    } else {
                        $uploadedfile = $_FILES['profile-pic']['tmp_name'];
                        $src = imagecreatefromgif($uploadedfile);
                    }
                    list($width, $height) = getimagesize($uploadedfile);

                    $newwidth = 174;
                    $newheight = ($height / $width) * $newwidth;
                    $tmp = imagecreatetruecolor($newwidth, $newheight);
                    imagecopyresampled($tmp, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

                    $name_id = explode('/', $id_user);
                    $name_id = $name_id[0] . $name_id[1] . $name_id[2] . $name_id[3] . $name_id[4];
                    $filename = $name_id . ".jpeg";

                    $pro = imagejpeg($tmp, $uploadDir . $filename, 100);

                    imagedestroy($src);
                    imagedestroy($tmp);

                    if ($pro == true) {
                        // Update profile picture filename in database
                        $objCtrl->update('m_user', array(
                            'profile' => $filename,
                        ), array('id_user' => $id_user));
                    } else {
                        $msg = "File uploading failed.";
                    }
                } else {
                    $msg = "Sorry, only .gif, .jpg and .png are allowed!";
                }
            } else {
                $msg = "Sorry, File must be under 10 MB!";
            }
        } else {
            $msg = "Profile picture is required!";
        }
    } else {
        $msg = "An account with this email already exists.";
    }
    echo json_encode($msg);
    break;



    case 'logout':
    foreach($_COOKIE AS $key => $value) {

      $objCtrl->setCookies($key,"");
  }
        // echo json_encode($_COOKIE);
  header('Location: ../system/');
  break;

  default:
      # code...
  break;
}

?>