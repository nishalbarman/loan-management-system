<?php require_once('../Connections/mlms.php'); ?>
<?php
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

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_viewpay = 10;
$pageNum_viewpay = 0;
if (isset($_GET['pageNum_viewpay'])) {
    $pageNum_viewpay = $_GET['pageNum_viewpay'];
}
$startRow_viewpay = $pageNum_viewpay * $maxRows_viewpay;

mysqli_select_db($mlms, $database_mlms);
$query_viewpay = "SELECT * FROM payment";
$query_limit_viewpay = sprintf("%s LIMIT %d, %d", $query_viewpay, $startRow_viewpay, $maxRows_viewpay);
$viewpay = mysqli_query($mlms, $query_limit_viewpay) or die(mysql_error());
$row_viewpay = mysqli_fetch_assoc($viewpay);

if (empty($row_viewpay)) {
    $row_viewpay = array("id" => "1", "paymentId" => "No Data Found", "memberId" => "No Data Found", "fName" => "No Data Found", "lName" => "No Data Found", "amount" => "No Data Found", "phone" => "No Data Found", "payment_date" => "No Data Found", "loanType" => "No Data Found");
}

if (isset($_GET['totalRows_viewpay'])) {
    $totalRows_viewpay = $_GET['totalRows_viewpay'];
} else {
    $all_viewpay = mysqli_query($mlms, $query_viewpay);
    $totalRows_viewpay = mysqli_num_rows($all_viewpay);
}
$totalPages_viewpay = ceil($totalRows_viewpay / $maxRows_viewpay) - 1;

$queryString_viewpay = "";
if (!empty($_SERVER['QUERY_STRING'])) {
    $params = explode("&", $_SERVER['QUERY_STRING']);
    $newParams = array();
    foreach ($params as $param) {
        if (
            stristr($param, "pageNum_viewpay") == false &&
            stristr($param, "totalRows_viewpay") == false
        ) {
            array_push($newParams, $param);
        }
    }
    if (count($newParams) != 0) {
        $queryString_viewpay = "&" . htmlentities(implode("&", $newParams));
    }
}
$queryString_viewpay = sprintf("&totalRows_viewpay=%d%s", $totalRows_viewpay, $queryString_viewpay);
?>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Admin</title>
    <link rel="stylesheet" type="text/css" href="../Assets/css/style.css">
    <style>
        /* form {
        padding: 30px;
        border: 1px solid greenyellow;
        display: inline-block;
        border-radius: 20px;
        width: 60%;
    }

    table {
        width: 100%;
    }

    input {
        height: 30px;
    } */
    </style>

</head>
<body>
    <?php include '../header/admin.php'; ?>
    <p></p>
    <center>
        <div class="main">
            <div class="container">
                <table border="0" align="center" class="responsive-table">
                    <h3 class="llTl"> All payments</h3>
                    <tr bgcolor="#33CCFF">
                        <td>Payment Id</td>
                        <td>ID/Passport No</td>
                        <td>First name</td>
                        <td>Second name</td>
                        <td>Amount</td>
                        <td>Phone number</td>
                        <td>Payment date</td>
                        <td>Update</td>
                        <td>Delete</td>
                    </tr>
                    <?php do { ?>
                        <tr>

                            <td><a href="paymentsview.php?recordID=<?php echo $row_viewpay['paymentId']; ?>">
                                    <?php echo $row_viewpay['paymentId']; ?>&nbsp; </a></td>
                            <td>
                                <?php echo $row_viewpay['memberId']; ?>&nbsp;
                            </td>
                            <td>
                                <?php echo $row_viewpay['fName']; ?>&nbsp;
                            </td>
                            <td>
                                <?php echo $row_viewpay['lName']; ?>&nbsp;
                            </td>
                            <td>
                                <?php echo $row_viewpay['amount']; ?>&nbsp;
                            </td>
                            <td>
                                <?php echo $row_viewpay['phone']; ?>&nbsp;
                            </td>
                            <td>
                                <?php echo $row_viewpay['payment_date']; ?>&nbsp;
                            </td>
                            <td><a href="updatepayment.php?paymentId=<?php echo $row_viewpay['paymentId']; ?>"
                                    style="text-decoration:none">
                                    <font color="#0033FF">EDIT</font>
                                </a></td>
                            <td><a href="deletepayment.php?paymentId=<?php echo $row_viewpay['paymentId']; ?>"
                                    style="text-decoration:none">
                                    <font color="#FF0000">DELETE</font>
                                </a></td>
                        </tr>
                    <?php } while ($row_viewpay = mysqli_fetch_assoc($viewpay)); ?>
                </table>
                Records
                <?php echo ($startRow_viewpay + 1) ?> to
                <?php echo min($startRow_viewpay + $maxRows_viewpay, $totalRows_viewpay) ?> of
                <?php echo $totalRows_viewpay ?>
                <br />
                <table border="0">
                    <tr>
                        <td>
                            <?php if ($pageNum_viewpay > 0) { // Show if not first page ?>
                                <a
                                    href="<?php printf("%s?pageNum_viewpay=%d%s", $currentPage, 0, $queryString_viewpay); ?>">First</a>
                            <?php } // Show if not first page ?>
                        </td>
                        <td>
                            <?php if ($pageNum_viewpay > 0) { // Show if not first page ?>
                                <a
                                    href="<?php printf("%s?pageNum_viewpay=%d%s", $currentPage, max(0, $pageNum_viewpay - 1), $queryString_viewpay); ?>">Previous</a>
                            <?php } // Show if not first page ?>
                        </td>
                        <td>
                            <?php if ($pageNum_viewpay < $totalPages_viewpay) { // Show if not last page ?>
                                <a
                                    href="<?php printf("%s?pageNum_viewpay=%d%s", $currentPage, min($totalPages_viewpay, $pageNum_viewpay + 1), $queryString_viewpay); ?>">Next</a>
                            <?php } // Show if not last page ?>
                        </td>
                        <td>
                            <?php if ($pageNum_viewpay < $totalPages_viewpay) { // Show if not last page ?>
                                <a
                                    href="<?php printf("%s?pageNum_viewpay=%d%s", $currentPage, $totalPages_viewpay, $queryString_viewpay); ?>">Last</a>
                            <?php } // Show if not last page ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </center>
    <script>
        function openNav() {
            document.getElementById("mySidenav").style.width = "250px";
            document.getElementById("main").style.marginLeft = "250px";
        }

        function closeNav() {
            document.getElementById("mySidenav").style.width = "0";
            document.getElementById("main").style.marginLeft = "0";
        }
    </script>
    <script>
        /* Loop through all dropdown buttons to toggle between hiding and showing its dropdown content - This allows the user to have multiple dropdowns without any conflict */
        var dropdown = document.getElementsByClassName("dropdown-btn");
        var i;

        for (i = 0; i < dropdown.length; i++) {
            dropdown[i].addEventListener("click", function () {
                this.classList.toggle("active");
                var dropdownContent = this.nextElementSibling;
                if (dropdownContent.style.display === "block") {
                    dropdownContent.style.display = "none";
                } else {
                    dropdownContent.style.display = "block";
                }
            });
        }
    </script>

</body>
</html>
<?php
mysqli_free_result($viewpay);
?>