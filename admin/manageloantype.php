<?php require_once('../Connections/mlms.php'); ?>
<?php

session_start();
if (!(isset($_SESSION['login']) && $_SESSION['login'] === true)) {
    header("location: ./index.php");
    exit;
}

$maxRows_DetailRS1 = 10;
$pageNum_DetailRS1 = 0;
if (isset($_GET['pageNum_DetailRS1'])) {
    $pageNum_DetailRS1 = $_GET['pageNum_DetailRS1'];
}
$startRow_DetailRS1 = $pageNum_DetailRS1 * $maxRows_DetailRS1;

$colname_DetailRS1 = "-1";
if (isset($_GET['recordID'])) {
    $colname_DetailRS1 = $_GET['recordID'];
}
mysqli_select_db($mlms, $database_mlms);
$query_DetailRS1 = sprintf("SELECT id, loanType, `description` FROM loantype WHERE id = %s", $colname_DetailRS1);
$query_limit_DetailRS1 = sprintf("%s LIMIT %d, %d", $query_DetailRS1, $startRow_DetailRS1, $maxRows_DetailRS1);
$DetailRS1 = mysqli_query($mlms, $query_limit_DetailRS1) or die(mysql_error());
$row_DetailRS1 = mysqli_fetch_assoc($DetailRS1);

if (isset($_GET['totalRows_DetailRS1'])) {
    $totalRows_DetailRS1 = $_GET['totalRows_DetailRS1'];
} else {
    $all_DetailRS1 = mysqli_query($mlms, $query_DetailRS1);
    $totalRows_DetailRS1 = mysqli_num_rows($all_DetailRS1);
}
$totalPages_DetailRS1 = ceil($totalRows_DetailRS1 / $maxRows_DetailRS1) - 1;
?>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <h2>Loan Management System</h2>
</head>

<body>

    <table border="1" align="center">
        <tr>
            <td>id</td>
            <td>
                <?php echo $row_DetailRS1['id']; ?>
            </td>
        </tr>
        <tr>
            <td>loanType</td>
            <td>
                <?php echo $row_DetailRS1['loanType']; ?>
            </td>
        </tr>
        <tr>
            <td>description</td>
            <td>
                <?php echo $row_DetailRS1['description']; ?>
            </td>
        </tr>
    </table>
</body>
</html>
<?php
mysqli_free_result($DetailRS1);
?>