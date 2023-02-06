<?php require_once('Connections/mlms.php'); ?>
<?php

session_start();

$tablename = $_GET['table'];

$sql = "SELECT * FROM " . $tablename . ";";
$res = mysqli_query($mlms, $sql);

$array = mysqli_fetch_all($res, MYSQLI_ASSOC);


print_r(json_encode($array));

exit;

?>