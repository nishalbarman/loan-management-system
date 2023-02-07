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

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_Recordset1 = 10;
$pageNum_Recordset1 = 0;
if (isset($_GET['pageNum_Recordset1'])) {
    $pageNum_Recordset1 = $_GET['pageNum_Recordset1'];
}
$startRow_Recordset1 = $pageNum_Recordset1 * $maxRows_Recordset1;

mysqli_select_db($mlms, $database_mlms);
$memberId = $_SESSION['MM_Username'];
$query_Recordset1 = "SELECT paymentId, memberId, fName, lName, amount, phone, payment_date FROM payment WHERE memberID=$memberId";
$query_limit_Recordset1 = sprintf("%s LIMIT %d, %d", $query_Recordset1, $startRow_Recordset1, $maxRows_Recordset1);
$Recordset1 = mysqli_query($mlms, $query_limit_Recordset1);
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);

if (empty($row_Recordset1)) {
    $row_Recordset1 = array("paymentId" => "No Data Found", "memberId" => "No Data Found", "fName" => "No Data Found", "lName" => "No Data Found", "amount" => "No Data Found", "phone" => "No Data Found", "payment_date" => "No Data Found");
}

if (isset($_GET['totalRows_Recordset1'])) {
    $totalRows_Recordset1 = $_GET['totalRows_Recordset1'];
} else {
    $all_Recordset1 = mysqli_query($mlms, $query_Recordset1);
    $totalRows_Recordset1 = mysqli_num_rows($all_Recordset1);
}
$totalPages_Recordset1 = ceil($totalRows_Recordset1 / $maxRows_Recordset1) - 1;

$queryString_Recordset1 = "";
if (!empty($_SERVER['QUERY_STRING'])) {
    $params = explode("&", $_SERVER['QUERY_STRING']);
    $newParams = array();
    foreach ($params as $param) {
        if (
            stristr($param, "pageNum_Recordset1") == false &&
            stristr($param, "totalRows_Recordset1") == false
        ) {
            array_push($newParams, $param);
        }
    }
    if (count($newParams) != 0) {
        $queryString_Recordset1 = "&" . htmlentities(implode("&", $newParams));
    }
}
$queryString_Recordset1 = sprintf("&totalRows_Recordset1=%d%s", $totalRows_Recordset1, $queryString_Recordset1);
?>

<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Admin</title>
    <link rel="stylesheet" type="text/css" href="Assets/css/style.css">
</head>
<body>
    <?php include 'header/user.php'; ?>
    <p></p>
    <center>
        <div class="main">
            <div class="container">
                <h3>Payment history</h3>
                <table border="0" align="center" class="responsive-table">
                    <tr bgcolor="#33CCFF">
                        <td>Payment Id</td>
                        <td>ID/Passport No</td>
                        <td>First name</td>
                        <td>Last Name</td>
                        <td>Amount</td>
                        <td>Phone no</td>
                        <td>Payment date</td>
                    </tr>
                    <?php do { ?>
                        <tr>
                            <td><a href="paymentlist.php?recordID=<?php echo $row_Recordset1['paymentId']; ?>">
                                    <?php echo $row_Recordset1['paymentId']; ?>&nbsp; </a></td>
                            <td>
                                <?php echo $row_Recordset1['memberId']; ?>&nbsp;
                            </td>
                            <td>
                                <?php echo $row_Recordset1['fName']; ?>&nbsp;
                            </td>
                            <td>
                                <?php echo $row_Recordset1['lName']; ?>&nbsp;
                            </td>
                            <td>
                                <?php echo $row_Recordset1['amount']; ?>&nbsp;
                            </td>
                            <td>
                                <?php echo $row_Recordset1['phone']; ?>&nbsp;
                            </td>
                            <td>
                                <?php echo $row_Recordset1['payment_date']; ?>&nbsp;
                            </td>
                        </tr>
                    <?php } while ($row_Recordset1 = mysqli_fetch_assoc($Recordset1)); ?>
                </table>
                Records
                <?php echo ($startRow_Recordset1 + 1) ?> to
                <?php echo min($startRow_Recordset1 + $maxRows_Recordset1, $totalRows_Recordset1) ?> of
                <?php echo $totalRows_Recordset1 ?>
                <br />
                <table border="0">
                    <tr>
                        <td>
                            <?php if ($pageNum_Recordset1 > 0) { // Show if not first page ?>
                                <a
                                    href="<?php printf("%s?pageNum_Recordset1=%d%s", $currentPage, 0, $queryString_Recordset1); ?>">First</a>
                            <?php } // Show if not first page ?>
                        </td>
                        <td>
                            <?php if ($pageNum_Recordset1 > 0) { // Show if not first page ?>
                                <a
                                    href="<?php printf("%s?pageNum_Recordset1=%d%s", $currentPage, max(0, $pageNum_Recordset1 - 1), $queryString_Recordset1); ?>">Previous</a>
                            <?php } // Show if not first page ?>
                        </td>
                        <td>
                            <?php if ($pageNum_Recordset1 < $totalPages_Recordset1) { // Show if not last page ?>
                                <a
                                    href="<?php printf("%s?pageNum_Recordset1=%d%s", $currentPage, min($totalPages_Recordset1, $pageNum_Recordset1 + 1), $queryString_Recordset1); ?>">Next</a>
                            <?php } // Show if not last page ?>
                        </td>
                        <td>
                            <?php if ($pageNum_Recordset1 < $totalPages_Recordset1) { // Show if not last page ?>
                                <a
                                    href="<?php printf("%s?pageNum_Recordset1=%d%s", $currentPage, $totalPages_Recordset1, $queryString_Recordset1); ?>">Last</a>
                            <?php } // Show if not last page ?>
                        </td>
                    </tr>
                </table>
            </div>

        </div>
    </center>
    <script>
        function openNav() {
            document.getElementById("mySidebar").style.width = "250px";
            document.getElementById("main").style.marginLeft = "250px";
        }

        function closeNav() {
            document.getElementById("mySidebar").style.width = "0";
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
mysqli_free_result($Recordset1);
?>