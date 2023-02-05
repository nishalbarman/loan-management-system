<?php require_once('Connections/mlms.php'); ?>
<?php

session_start();

$tablename = $_GET['table'];

$sql = "SELECT * FROM " . $tablename . ";";
$res = mysqli_query($mlms, $sql);
// $num = mysqli_num_rows($res);

// $sql = "SELECT `amount` FROM " . $value . " limit 1";
// $res = mysqli_query($mlms, $sql);
$array = mysqli_fetch_all($res, MYSQLI_ASSOC);

// $table = "<table class='table'><tr><th>Month</th><th>Amount</th><th>Status</th></tr><tr>".while($row = mysqli_fetch_assoc($res) {
//     echo <td></td>
// })."</tr></table>"

print_r(json_encode($array));
// print_r(json_encode($array));
exit;

?>