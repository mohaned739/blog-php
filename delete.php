<?php
$conn = mysqli_connect("localhost","root","","blog");
if(!$conn){
    echo mysqli_connect_error();
    exit;
    }
$id= filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);
$select="select * from users where users.id = $id limit 1";
$result=mysqli_query($conn,$select);
$row= mysqli_fetch_assoc($result);
if($row){
    $id=filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);
    $query="delete from users where users.id = $id";
if(mysqli_query($conn,$query)){
    header("Location: list.php");//redirect to this page
    exit;
}else{
    echo $query;
    echo mysqli_error($conn);
}
}
mysqli_close($conn);
?>