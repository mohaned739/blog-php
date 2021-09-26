<?php
$error_fields=array();
if($_SERVER['REQUEST_METHOD']=='POST'){
if(!(isset($_POST["name"])&& !empty($_POST["name"]))){
    $error_fields[]="name";
}
if(!(isset($_POST["email"])&& !empty($_POST["email"]) && filter_input(INPUT_POST,'email',FILTER_VALIDATE_EMAIL) )){
    $error_fields[]="email";
    }
if(!(isset($_POST["password"])&& strlen($_POST["password"])>5)){
        $error_fields[]="password";
    }

if($error_fields){
    header("Location: index.php?error_fields=".implode(",",$error_fields));
    exit;
}
$conn = mysqli_connect("localhost","root","","blog");
if(!$conn){
    echo mysqli_connect_error();
    exit;
}
$name=mysqli_escape_string($conn,$_POST["name"]);//escape any special characters to prevent sql injection
$email=mysqli_escape_string($conn,$_POST["email"]);//to prevent sql injection
$password=sha1($_POST["password"]);//encryption
$admin=(isset($_POST['admin'])) ? 1 : 0;
$uploads_dir=$_SERVER['DOCUMENT_ROOT'].'/Uploads';
// $uploads_dir='/Uploads';
$avatar='';
if($_FILES['avatar']['error']==UPLOAD_ERR_OK){
    $tmp_name=$_FILES['avatar']['tmp_name'];
    $avatar=basename($_FILES['avatar']['name']);
    move_uploaded_file($tmp_name,$uploads_dir/$name.$avatar);
}else{
    echo "File can't be uploaded";
    exit;
}
$query="Insert into users (name,email,password,avatar,admin) values (' $name','$email','$password','$avatar','$admin')";
if(mysqli_query($conn,$query)){
    header("Location: list.php");//redirect to this page
    exit;
}else{
    echo $query;
    echo mysqli_error($conn);
}



mysqli_close($conn);
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
    <?php
    $arr_errors=array();
    if(isset($_GET["error_fields"])){
    $arr_errors=explode(",",$_GET['error_fields']);
    }
    ?>
    <form method="post" enctype="multipart/form-data">
        <label for="name">Name</label>
        <input type="text" name="name" id="name" value="<?= isset($_Post['name'])?$_Post['name']:'' ?>"><?php if(in_array('name',$arr_errors)){
            echo "<b style='color:red;'>*Please enter your name</b>";} ?></br></br>
        <label for="email" >E-mail</label>
        <input type="email" name="email" id="email" value="<?= isset($_Post['email'])?$_Post['email']:'' ?>"><?php if(in_array('email',$arr_errors)){
            echo "<b style='color:red;'>*Please enter a valid email</b>";} ?></br></br>
        <label for="password">Password</label>
        <input type="password" name="password" id="password" value="<?= isset($_Post['email'])?$_Post['email']:'' ?>"><?php if(in_array('password',$arr_errors)){
            echo "<b style='color:red;'>*Please enter a password not less than 6 characters</b>";} ?></br></br>
        <input type="checkbox" name="admin" <?= (isset($_POST['admin']))? 'checked': '' ?>>Admin</br></br>
        <label for="avatar">Avatar</label>
        <input type="file" id="avatar" name="avatar"></br></br>
        <input type="submit" name="submit" value="Register">

    </form>
</body>
</html>