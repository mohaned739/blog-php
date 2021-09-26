<?php
session_start();
if(isset($_SESSION['id'])){
    echo '<p>Welcom '.$_SESSION['email'].' <a href="logout.php">Logout</a></p>';
}else{
    header("location: login.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List users</title>
</head>
<body>
    <?php
        $conn = mysqli_connect("localhost","root","","blog");
        if(!$conn){
            echo mysqli_connect_error();
            exit;
        }
        $query="select * from users";
        if(isset($_GET['search'])){
            $search=mysqli_escape_string($conn,$_GET['search']);
            $query.=" where users.name LIKE '%".$search."%' or users.email like '%".$search."%'";
        }
        $result=mysqli_query($conn,$query);
        ?>
        <h1>List Users</h1>
        <form method="GET">
            <input type="text" name="search" placeholder="Enter {Name} or {Email} to search">
            <input type="submit" value="Search">
        </form>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Avatar</th>
                    <th>Admin</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    while($row=mysqli_fetch_assoc($result)){
                ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['name'] ?></td>
                    <td><?= $row['email'] ?></td>
                    <td><img src="<?= $row['name'].$row['avatar'] ?>" alt="Avatar"></td>
                    <td><?=($row['admin'])? 'Yes' : 'No' ?></td>
                    <td><a href="edit.php?id=<?= $row['id']?>">Edit</a> | <a href="delete.php?id=<?= $row['id']?>">Delete</a></td>
                </tr>
                <?php
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" style="text-align:center"><?= mysqli_num_rows($result)?> Users</td>
                        <td colspan="3" style="text-align:center"><a href="index.php">Add User</a></td>
                </tr>
                </tfoot>
        </table>
        <?php
            mysqli_free_result($result);
            mysqli_close($conn);
        ?>
</body>
</html>