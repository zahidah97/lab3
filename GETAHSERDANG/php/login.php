<?php
session_start();
include_once("dbconnect.php");
$email = trim($_POST['email']);
$password = trim(sha1($_POST['password']));
$sqllogin = "SELECT * FROM tbl_user WHERE email = '$email' AND password = '$password'";

$select_stmt = $conn->prepare($sqllogin);
$select_stmt->execute();
$row = $select_stmt->fetch(PDO::FETCH_ASSOC);
if ($select_stmt->rowCount() > 0) {
    $_SESSION["session_id"] = session_id();
    $_SESSION["email"] = $email;
    $_SESSION["name"] = $row['name'];
    echo "<script> alert('Login successful')</script>";
    echo "<script> window.location.replace('../index.php')</script>";
} else {
    session_unset();
    session_destroy();
    echo "<script> alert('Login fail')</script>";
    echo "<script> window.location.replace('../html/login.html')</script>";
}
?>