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
        "UPDATE payment SET paymentId=%s, memberId=%s, fName=%s, lName=%s, amount=%s, phone=%s WHERE id=%s",
        GetSQLValueString($_POST['paymentId'], "text"),
        GetSQLValueString($_POST['memberId'], "int"),
        GetSQLValueString($_POST['fName'], "text"),
        GetSQLValueString($_POST['lName'], "text"),
        GetSQLValueString($_POST['amount'], "int"),
        GetSQLValueString($_POST['phone'], "text"),
        GetSQLValueString($_POST['id'], "int")
    );

    mysqli_select_db($mlms, $database_mlms);
    $Result1 = mysqli_query($mlms, $updateSQL) or die(mysql_error());

    $updateGoTo = "updatepayment.php";
    if (isset($_SERVER['QUERY_STRING'])) {
        $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
        $updateGoTo .= $_SERVER['QUERY_STRING'];
    }
    header(sprintf("Location: %s", $updateGoTo));
}

mysqli_select_db($mlms, $database_mlms);
$query_updpaym = "SELECT * FROM payment";
$updpaym = mysqli_query($mlms, $query_updpaym) or die(mysql_error());
$row_updpaym = mysqli_fetch_assoc($updpaym);
$totalRows_updpaym = mysqli_num_rows($updpaym);
?>

<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Admin</title>
    <link rel="stylesheet" type="text/css" href="../Assets/css/style.css">
    <style>
        form {
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
        }
    </style>
</head>
<body>
    <?php include '../header/admin.php'; ?>
    <p></p>
    <center>
        <div class="main">
            <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
                <table align="center">
                    <tr valign="baseline">
                        <td nowrap="nowrap" align="right" style="text-align: left;">Payment Id:</td>
                        <td><input type="text" name="paymentId"
                                value="<?php echo htmlentities($row_updpaym['paymentId'], ENT_COMPAT, 'utf-8'); ?>"
                                size="32" /></td>
                    </tr>
                    <tr valign="baseline">
                        <td nowrap="nowrap" align="right" style="text-align: left;">Member Id:</td>
                        <td><input type="text" name="memberId" value="<?php echo $row_updpaym['memberId'] ?>" /></td>
                    </tr>
                    <tr valign=" baseline">
                        <td nowrap="nowrap" align="right" style="text-align: left;">First name:</td>
                        <td><input type="text" name="fName"
                                value="<?php echo htmlentities($row_updpaym['fName'], ENT_COMPAT, 'utf-8'); ?>"
                                size="32" /></td>
                    </tr>
                    <tr valign="baseline">
                        <td nowrap="nowrap" align="right" style="text-align: left;">Last name:</td>
                        <td><input type="text" name="lName"
                                value="<?php echo htmlentities($row_updpaym['lName'], ENT_COMPAT, 'utf-8'); ?>"
                                size="32" /></td>
                    </tr>
                    <tr valign="baseline">
                        <td nowrap="nowrap" align="right" style="text-align: left;">Amount:</td>
                        <td><input type="text" name="amount"
                                value="<?php echo htmlentities($row_updpaym['amount'], ENT_COMPAT, 'utf-8'); ?>"
                                size="32" /></td>
                    </tr>
                    <tr valign="baseline">
                        <td nowrap="nowrap" align="right" style="text-align: left;">Phone:</td>
                        <td><input type="text" name="phone"
                                value="<?php echo htmlentities($row_updpaym['phone'], ENT_COMPAT, 'utf-8'); ?>"
                                size="32" /></td>
                    </tr>
                    <tr valign="baseline">
                        <!-- <td nowrap="nowrap" align="right" style="text-align: left;">&nbsp;</td> -->
                        <!-- <td></td> -->
                    </tr>
                </table>
                <br>
                <input class="frmBtn" type="submit" value="Update" style="width: 75%; color: black; height: 40px;" />
                <input type="hidden" name="MM_update" value="form1" />
                <input type="hidden" name="id" value="<?php echo $row_updpaym['id']; ?>" />
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
            document.getElementById("mySidebav").style.width = "0";
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
mysqli_free_result($updpaym);
?>