<?php require_once('Connections/mlms.php'); ?>
<?php

session_start();

$value = $_GET['loan'];

$sql = "SELECT * FROM " . $value . " WHERE status='Paid'";
$res = mysqli_query($mlms, $sql);
$num = mysqli_num_rows($res);

$sql = "SELECT `amount` FROM " . $value . " limit 1";
$res = mysqli_query($mlms, $sql);
$amount = mysqli_fetch_assoc($res);
// $array = mysqli_fetch_all($res, MYSQLI_ASSOC);

print_r(json_encode(array("result" => $num, "amount" => $amount['amount'])));
// print_r(json_encode($array));
exit;

?>