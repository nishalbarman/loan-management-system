<?php require_once('Connections/mlms.php'); ?>
<?php
session_start();
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

$maxRows_lonhist = 10;
$pageNum_lonhist = 0;
if (isset($_GET['pageNum_lonhist'])) {
    $pageNum_lonhist = $_GET['pageNum_lonhist'];
}
$startRow_lonhist = $pageNum_lonhist * $maxRows_lonhist;
mysqli_select_db($mlms, $database_mlms);
$memberId = $_SESSION['MM_Username'];
$query_lonhist = "SELECT * FROM `loan` WHERE memberId=$memberId";
$query_limit_lonhist = "SELECT * FROM loan WHERE memberId=$memberId LIMIT $startRow_lonhist, $maxRows_lonhist";
$lonhist = mysqli_query($mlms, $query_limit_lonhist) or die(mysql_error());
$row_lonhist = mysqli_fetch_assoc($lonhist);
if (empty($row_lonhist)) {
    $row_lonhist = array("loanId" => "No Data Found", "memberId" => "No Data Found", "loanType" => "No Data Found", "income" => "No Data Found", "amount" => "No Data Found", "interest" => "No Data Found", "payment_term" => "No Data Found", "total_paid" => "No Data Found", "emi_per_month" => "No Data Found", "bankStatementPhoto" => "No Data Found", "security" => "No Data Found", "posting_date" => "No Data Found", "status" => "No Data Found", "adminRemark" => "No Data Found", "adminRemarkDate" => "No Data Found");
}

if (isset($_GET['totalRows_lonhist'])) {
    $totalRows_lonhist = $_GET['totalRows_lonhist'];
} else {
    $all_lonhist = mysqli_query($mlms, $query_lonhist);
    $totalRows_lonhist = mysqli_num_rows($all_lonhist);
}
$totalPages_lonhist = ceil($totalRows_lonhist / $maxRows_lonhist) - 1;

$queryString_lonhist = "";
if (!empty($_SERVER['QUERY_STRING'])) {
    $params = explode("&", $_SERVER['QUERY_STRING']);
    $newParams = array();
    foreach ($params as $param) {
        if (
            stristr($param, "pageNum_lonhist") == false &&
            stristr($param, "totalRows_lonhist") == false
        ) {
            array_push($newParams, $param);
        }
    }
    if (count($newParams) != 0) {
        $queryString_lonhist = "&" . htmlentities(implode("&", $newParams));
    }
}
$queryString_lonhist = sprintf("&totalRows_lonhist=%d%s", $totalRows_lonhist, $queryString_lonhist);
?>

<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Member</title>
    <link rel="stylesheet" type="text/css" href="Assets/css/style.css">
    <style>
        table th {
            text-align: center;
        }
    </style>
</head>
<body>

    <?php include 'header/user.php'; ?>
    <p></p>
    <center>
        <div class="main">
            <h3 class="llTl">Loan History</h3>
            <table border="0" align="center" class="responsive-table">
                <tr bgcolor="#33CCFF" bordercolordark="#000000">

                    <!-- <th>ID</th> -->
                    <th width="40px">MID</th>
                    <th>Amount</th>
                    <th>Monthly pay</th>

                    <th>Application date</th>
                    <th>Status</th>
                    <th>Admin Remark</th>

                </tr>
                <?php do { ?>
                    <tr>

                        <!-- <td><a href="loanhist.php?recordID=<?php echo $row_lonhist['loanId']; ?>">
                                                                        <?php echo $row_lonhist['loanId']; ?>&nbsp; </a></td> -->
                        <td>
                            <?php echo $row_lonhist['memberId']; ?>&nbsp;
                        </td>
                        <td>
                            <?php echo $row_lonhist['total_paid']; ?>&nbsp;
                        </td>
                        <td>
                            <?php echo $row_lonhist['emi_per_month']; ?>&nbsp;
                        </td>
                        <td>
                            <?php echo $row_lonhist['posting_date']; ?>&nbsp;
                        </td>
                        <td>
                            <font color=" #0066FF">
                                <?php echo $row_lonhist['status']; ?>
                            </font>&nbsp;
                        </td>
                        <td>
                            <?php echo $row_lonhist['adminRemark']; ?>&nbsp;
                        </td>
                    </tr>
                <?php } while ($row_lonhist = mysqli_fetch_assoc($lonhist)); ?>
            </table>
            <br />
            Records
            <?php echo ($startRow_lonhist + 1) ?> to
            <?php echo min($startRow_lonhist + $maxRows_lonhist, $totalRows_lonhist) ?> of
            <?php echo $totalRows_lonhist ?>

            <br />
            <table border="0" class="tableStyle">
                <tr>
                    <td>
                        <?php if ($pageNum_lonhist > 0) { // Show if not first page ?>
                            <a
                                href="<?php printf("%s?pageNum_lonhist=%d%s", $currentPage, 0, $queryString_lonhist); ?>">First</a>
                        <?php } // Show if not first page ?>
                    </td>
                    <td>
                        <?php if ($pageNum_lonhist > 0) { // Show if not first page ?>
                            <a
                                href=" <?php printf("%s?pageNum_lonhist=%d%s", $currentPage, max(0, $pageNum_lonhist - 1), $queryString_lonhist); ?>">Previous</a>
                        <?php } // Show if not first page ?>
                    </td>
                    <td>
                        <?php if ($pageNum_lonhist < $totalPages_lonhist) { // Show if not last page ?>
                            <a
                                href="<?php printf("%s?pageNum_lonhist=%d%s", $currentPage, min($totalPages_lonhist, $pageNum_lonhist + 1), $queryString_lonhist); ?>">Next</a>
                        <?php } // Show if not last page ?>
                    </td>
                    <td>
                        <?php if ($pageNum_lonhist < $totalPages_lonhist) { // Show if not last page ?>
                            <a
                                href=" <?php printf("%s?pageNum_lonhist=%d%s", $currentPage, $totalPages_lonhist, $queryString_lonhist); ?>">Last</a>
                        <?php } // Show if not last page ?>
                    </td>
                </tr>
            </table>

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
mysqli_free_result($lonhist);
?>