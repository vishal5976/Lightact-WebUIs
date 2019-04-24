<?php

$target_dir = "../assets/";
$target_file = $target_dir . basename($_FILES["headerimage"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
$uploadResult='';

//check if there is actually a filename attached
if($_FILES["headerimage"]["name"]!=='') {
	// Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["headerimage"]["tmp_name"]);
    if($check !== false) {
        $uploadResult="File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
		$uploadResult= "File is not an image.";
        $uploadOk = 0;
    }
}else{
	$target_file='';
	$uploadResult= "No file selected.";
	$uploadOk = 2;
}

// Check file size
if ($_FILES["headerimage"]["size"] > 500000 && $uploadOk==1) {
    $uploadResult= "Sorry, your file is too large.";
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" && $uploadOk==1) {
    $uploadResult= "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    /*$uploadResult= "Sorry, your file was not uploaded.";*/
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["headerimage"]["tmp_name"], $target_file)) {
        $uploadResult= "The file ". basename( $_FILES["headerimage"]["name"]). " has been uploaded.";
    } else {
        $uploadResult= "Sorry, there was an error uploading your file.";
    }
}

?>