<?php require_once('Connections/mlms.php'); ?>
<?php
session_start();
if (!(isset($_SESSION['login']) && $_SESSION['login'] === true)) {
    header("location: ./index.php");
    exit;
}

if (!function_exists("GetSQLValueString")) {
    function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "")
    {
        if (PHP_VERSION < 6) {
            $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
        }

        $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysqli_escape_string(mysqli_connect("localhost", "root", "", "lms"), $theValue);

        switch ($theType) {
            case "text":
                $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
                break;
            case "long":
            case "int":
                $theValue = ($theValue != "") ? intval($theValue) : "NULL";
                break;
            case "double":
                $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
                break;
            case "date":
                $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
                break;
            case "defined":
                $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
                break;
        }
        return $theValue;
    }
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
mysqli_select_db($database_mlms, $mlms);
$query_DetailRS1 = sprintf("SELECT memberId, loanType, income, amount, intereset, payment_term, total_paid, emi_per_month, posting_date, status, adminRemark FROM loan WHERE memberId = %s", GetSQLValueString($colname_DetailRS1, "int"));
$query_limit_DetailRS1 = sprintf("%s LIMIT %d, %d", $query_DetailRS1, $startRow_DetailRS1, $maxRows_DetailRS1);
$DetailRS1 = mysqli_query($mlms, $query_limit_DetailRS1, $mlms);
$row_DetailRS1 = mysqli_fetch_assoc($mlms, $DetailRS1);

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
            <td>memberId</td>
            <td>
                <?php echo $row_DetailRS1['memberId']; ?>
            </td>
        </tr>
        <tr>
            <td>loanType</td>
            <td>
                <?php echo $row_DetailRS1['loanType']; ?>
            </td>
        </tr>
        <tr>
            <td>income</td>
            <td>
                <?php echo $row_DetailRS1['income']; ?>
            </td>
        </tr>
        <tr>
            <td>amount</td>
            <td>
                <?php echo $row_DetailRS1['amount']; ?>
            </td>
        </tr>
        <tr>
            <td>intereset</td>
            <td>
                <?php echo $row_DetailRS1['intereset']; ?>
            </td>
        </tr>
        <tr>
            <td>payment_term</td>
            <td>
                <?php echo $row_DetailRS1['payment_term']; ?>
            </td>
        </tr>
        <tr>
            <td>total_paid</td>
            <td>
                <?php echo $row_DetailRS1['total_paid']; ?>
            </td>
        </tr>
        <tr>
            <td>emi_per_month</td>
            <td>
                <?php echo $row_DetailRS1['emi_per_month']; ?>
            </td>
        </tr>
        <tr>
            <td>posting_date</td>
            <td>
                <?php echo $row_DetailRS1['posting_date']; ?>
            </td>
        </tr>
        <tr>
            <td>status</td>
            <td>
                <?php echo $row_DetailRS1['status']; ?>
            </td>
        </tr>
        <tr>
            <td>adminRemark</td>
            <td>
                <?php echo $row_DetailRS1['adminRemark']; ?>
            </td>
        </tr>
    </table>
</body>
</html>
<?php
mysqli_free_result($DetailRS1);
?>