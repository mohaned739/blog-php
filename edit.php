<?php
$error_fields=array();
$conn = mysqli_connect("localhost","root","","blog");
if(!$conn){
    echo mysqli_connect_error();
    exit;
    }
$id= filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);
$select="select * from users where users.id = $id limit 1";
$result=mysqli_query($conn,$select);
$row= mysqli_fetch_assoc($result);

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

if(!$error_fields){
$id=filter_input(INPUT_POST,'id',FILTER_SANITIZE_NUMBER_INT);
$name=mysqli_escape_string($conn,$_POST["name"]);//escape any special characters to prevent sql injection
$email=mysqli_escape_string($conn,$_POST["email"]);//to prevent sql injection
$password=(!empty($_POST['password']))?sha1($_POST["password"]) : $row['password'];//encryption
$admin=(isset($_POST['admin'])) ? 1 : 0;
$query="update users set name = '$name' , email='$email' , password='$password' , admin='$admin' where users.id = $id";
if(mysqli_query($conn,$query)){
    header("Location: list.php");//redirect to this page
    exit;
}else{
    echo $query;
    echo mysqli_error($conn);
}
}
}



mysqli_close($conn);
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
    <form method="post">
        <input type="hidden" name="id" id="id" value="<?= isset($row['id'])? $row['id'] : ''?>">
        <label for="name">Name</label>
        <input type="text" name="name" id="name" value="<?= isset($row['name'])?$row['name']:'' ?>"><?php if(in_array('name',$arr_errors)){
            echo "<b style='color:red;'>*Please enter your name</b>";} ?></br></br>
        <label for="email" >E-mail</label>
        <input type="email" name="email" id="email" value="<?= isset($row['email'])?$row['email']:'' ?>"><?php if(in_array('email',$arr_errors)){
            echo "<b style='color:red;'>*Please enter a valid email</b>";} ?></br></br>
        <label for="password">Password</label>
        <input type="password" name="password" id="password" value="<?= isset($row['email'])?$row['email']:'' ?>"><?php if(in_array('password',$arr_errors)){
            echo "<b style='color:red;'>*Please enter a password not less than 6 characters</b>";} ?></br></br>
        <input type="checkbox" name="admin" <?= ($row['admin']) ? 'checked': ''?>>Admin</br></br>
        <input type="submit" name="submit" value="Edit">

    </form>
</body>
</html>