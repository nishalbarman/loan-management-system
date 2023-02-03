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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
    $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
    $updateSQL = sprintf(
        "UPDATE loantype SET `description`=%s WHERE loanType=%s",
        GetSQLValueString($_POST['description'], "text"),
        GetSQLValueString($_POST['loanType'], "text")
    );

    mysqli_select_db($mlms, $database_mlms);
    $Result1 = mysqli_query($mlms, $updateSQL) or die(mysql_error());

    $updateGoTo = "updateloantype.php";
    if (isset($_SERVER['QUERY_STRING'])) {
        $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
        $updateGoTo .= $_SERVER['QUERY_STRING'];
    }
    header(sprintf("Location: %s", $updateGoTo));
}

mysqli_select_db($mlms, $database_mlms);
$query_updlontyp = "SELECT loanType, `description` FROM loantype";
$updlontyp = mysqli_query($mlms, $query_updlontyp) or die(mysql_error());
$row_updlontyp = mysqli_fetch_assoc($updlontyp);
$totalRows_updlontyp = mysqli_num_rows($updlontyp);
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
            <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
                <table align="center">
                    <tr valign="baseline">
                        <td nowrap="nowrap" align="right">Loan type:</td>
                        <td>
                            <?php echo $row_updlontyp['loanType']; ?>
                        </td>
                    </tr>
                    <tr valign="baseline">
                        <td nowrap="nowrap" align="right">Description:</td>
                        <td><input type="text" name="description"
                                value="<?php echo htmlentities($row_updlontyp['description'], ENT_COMPAT, 'utf-8'); ?>"
                                size="32" />
                        </td>
                    </tr>
                    <tr valign="baseline">
                        <td nowrap="nowrap" align="right">&nbsp;</td>
                        <td><input type="submit" value="Update record" /></td>
                    </tr>
                </table>
                <input type="hidden" name="MM_update" value="form1" />
                <input type="hidden" name="loanType" value="<?php echo $row_updlontyp['loanType']; ?>" />
            </form>
            <p>&nbsp;</p>
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
        dropdown[i].addEventListener("click", function() {
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
mysqli_free_result($updlontyp);
?>