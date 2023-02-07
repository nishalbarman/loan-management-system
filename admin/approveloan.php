<?php require_once('../Connections/mlms.php'); ?>
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
    $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
    $updateSQL = sprintf(
        "UPDATE loan SET memberId=%s, loanType=%s, total_paid=%s, posting_date=%s, status=%s, adminRemark=%s, adminRemarkDate=%s WHERE loanId=%s",
        GetSQLValueString($_POST['memberId'], "int"),
        GetSQLValueString($_POST['loanType'], "text"),
        GetSQLValueString($_POST['total_paid'], "int"),
        GetSQLValueString($_POST['posting_date'], "date"),
        GetSQLValueString($_POST['status'], "text"),
        GetSQLValueString($_POST['adminRemark'], "text"),
        GetSQLValueString($_POST['adminRemarkDate'], "date"),
        GetSQLValueString($_POST['loanId'], "int")
    );

    mysqli_select_db($mlms, $database_mlms);
    $Result1 = mysqli_query($mlms, $updateSQL) or die(mysql_error());

    $updateGoTo = "approveloan.php";
    if (isset($_SERVER['QUERY_STRING'])) {
        $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
        $updateGoTo .= $_SERVER['QUERY_STRING'];
    }
    header(sprintf("Location: %s", $updateGoTo));
}

mysqli_select_db($mlms, $database_mlms);
$query_Recordset1 = "SELECT * FROM loan";
$Recordset1 = mysqli_query($mlms, $query_Recordset1) or die(mysql_error());
$row_Recordset1 = mysqli_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);
?>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Admin</title>
    <link rel="stylesheet" type="text/css" href="../Assets/css/style.css">
    <style>
        td {
            font-size: 20px;
        }

        form {
            padding: 30px;
            border: 1px solid greenyellow;
            display: inline-block;
            border-radius: 20px;
        }
    </style>
</head>
<body>
    <?php include '../header/admin.php'; ?>
    <p></p>
    <center>
        <div class="mainn">
            <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
                <table align="center">
                    <tr valign="baseline">
                        <td nowrap="nowrap" align="right" style="text-align: left;">Member ID</td>
                        <td><input type="text" name="memberId"
                                value="<?php echo htmlentities($row_Recordset1['memberId'], ENT_COMPAT, 'utf-8'); ?>"
                                size="32" /></td>
                    </tr>
                    <tr valign="baseline">
                        <td nowrap="nowrap" align="right" style="text-align: left;">Loan type:</td>
                        <td><input type="text" name="loanType"
                                value="<?php echo htmlentities($row_Recordset1['loanType'], ENT_COMPAT, 'utf-8'); ?>"
                                size="32" /></td>
                    </tr>
                    <tr valign="baseline">
                        <td nowrap="nowrap" align="right" style="text-align: left;">Amount:</td>
                        <td><input type="text" name="total_paid"
                                value="<?php echo htmlentities($row_Recordset1['total_paid'], ENT_COMPAT, 'utf-8'); ?>"
                                size="32" />
                        </td>
                    </tr>
                    <tr valign="baseline">
                        <td nowrap="nowrap" align="right" style="text-align: left;">Application date:</td>
                        <td><input type="text" name="posting_date"
                                value="<?php echo htmlentities($row_Recordset1['posting_date'], ENT_COMPAT, 'utf-8'); ?>"
                                size="32" />
                        </td>
                    </tr>
                    <tr valign="baseline">
                        <td nowrap="nowrap" align="right" style="text-align: left;">Status:</td>
                        <td><select name="status">
                                <option value="Approved">Approved</option>
                                <option value="Unapproved">Unapproved</option>
                            </select></td>
                    </tr>
                    <tr valign="baseline">
                        <td nowrap="nowrap" align="right" style="text-align: left;">AdminRemark:</td>
                        <td><input type="text" name="adminRemark" value="" size="32" required /></td>
                    </tr>
                    <tr valign="baseline">
                        <td nowrap="nowrap" align="right" style="text-align: left;">AdminRemarkDate:</td>
                        <td><input type="date" name="adminRemarkDate" value="" size="32" required /></td>
                    </tr>
                    <tr valign="baseline">
                        <td nowrap="nowrap" align="right">&nbsp;</td>
                        <td><input class="frmBtn" type="submit" value="Update record" /></td>
                    </tr>
                </table>
                <input type="hidden" name="MM_update" value="form1" />
                <input type="hidden" name="loanId" value="<?php echo $row_Recordset1['loanId']; ?>" />
            </form>
            <p>&nbsp;</p>
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