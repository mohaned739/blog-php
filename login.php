<?php
session_start();
if($_SERVER['REQUEST_METHOD']=='POST'){
    $conn = mysqli_connect("localhost","root","","blog");
    if(!$conn){
        echo mysqli_connect_error();
        exit;
    }
    $email=mysqli_escape_string($conn,$_POST['email']);
    $password=sha1($_POST['password']);
    $query="select * from users where email= '$email' and password='$password' limit 1";
    $result=mysqli_query($conn,$query);
    if($row=mysqli_fetch_assoc($result)){
        $_SESSION['id']=$row['id'];
        $_SESSION['email']=$row['email'];
        header("location: list.php");
        exit;
    }else{
        $error='Invalid email or password';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <?php if(isset($error)) echo $error; ?>
    <form method="post">
        <label for="email">Email </label>
        <input type="email" name="email" id="email" value="<?= isset($_Post['email'])?$_Post['email']:'' ?>"></br></br>
        <label for="password">Password</label>
        <input type="password" name="password" id="password" value="<?= isset($_Post['email'])?$_Post['email']:'' ?>"></br></br>
        <input type="submit" name="submit" value="Login">
    </form>
</body>
</html>