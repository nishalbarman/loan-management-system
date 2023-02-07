<?php require_once('../Connections/mlms.php'); ?>
<?php
session_start();
if (!(isset($_SESSION['login']) && $_SESSION['login'] === true)) {
    header("location: ./adminlogin.php");
    exit;
}
mysqli_select_db($mlms, $database_mlms);
$query_Recordset1 = "SELECT * FROM member";
$Recordset1 = mysqli_query($mlms, $query_Recordset1) or die(mysql_error());
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);

mysqli_select_db($mlms, $database_mlms);
$query_Recordset2 = "SELECT * FROM loanType";
$Recordset2 = mysqli_query($mlms, $query_Recordset2) or die(mysql_error());
$row_Recordset2 = mysqli_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysqli_num_rows($Recordset2);
?>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Admin</title>
    <!-- <link rel="stylesheet" type="text/css" href="../Assets/css/body.css">
    <link rel="stylesheet" type="text/css" href="../Assets/css/sidebar.css">
    <link rel="stylesheet" type="text/css" href="../Assets/css/dropdown.css" />
    <link rel="stylesheet" type="text/css" href="../font-awesome-4.7.0/css/font-awesome.min.css" /> -->
</head>
<body>
    <?php include '../header/admin.php'; ?>
    <p></p>
    <center>
        <div class="mainn">
            <table align="center" width="70%" height="40%">
                <tr>
                    <td align="center"><a href="displaymember.php" title="click to view your Members"
                            style="text-decoration:none"><img src="../Photos/customer.png" height="180px"
                                width="180px" /><br />
                            Total Users &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <!-- <?php echo $totalRows_Recordset1 ?> -->
                        </a></td>

                    <td align="center"><a href="pendingloans.php" title="click to view loan type"
                            style="text-decoration:none"><img src="../Photos/loan.png" height="180px"
                                width="180px" /><br />Pending Loans
                            type&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <!-- <?php echo $totalRows_Recordset2 ?> -->
                        </a></td>

                    <!-- <td align="center"><a href="reports.php" title="click to view customers Reports"
                            style="text-decoration:none"><img src="../Photos/reports.png" height="140px"
                                width="140px" /><br />Reports</a></td> -->
                </tr>
            </table>

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
mysqli_free_result($Recordset1);
?>