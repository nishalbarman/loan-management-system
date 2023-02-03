<?php require_once('../Connections/mlms.php'); ?>
<?php

session_start();

$member = $_GET['member'];

$sql = "SELECT * FROM `applied_loans` WHERE memberId=$member";
$res = mysqli_query($mlms, $sql);
$array = mysqli_fetch_all($res, MYSQLI_ASSOC);

print_r(json_encode($array));
exit();

?>