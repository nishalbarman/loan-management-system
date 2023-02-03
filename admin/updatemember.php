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
        "UPDATE member SET fName=%s, lName=%s, phone=%s, occupation=%s, email=%s, address=%s, county=%s, photo=%s WHERE memberId=%s",
        GetSQLValueString($_POST['fName'], "text"),
        GetSQLValueString($_POST['lName'], "text"),
        GetSQLValueString($_POST['phone'], "text"),
        GetSQLValueString($_POST['occupation'], "text"),
        GetSQLValueString($_POST['email'], "text"),
        GetSQLValueString($_POST['address'], "text"),
        GetSQLValueString($_POST['county'], "text"),
        GetSQLValueString($_POST['photo'], "text"),
        GetSQLValueString($_POST['memberId'], "int")
    );

    mysqli_select_db($mlms, $database_mlms);
    $Result1 = mysqli_query($mlms, $updateSQL) or die(mysql_error());

    $updateGoTo = "updatemember.php";
    if (isset($_SERVER['QUERY_STRING'])) {
        $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
        $updateGoTo .= $_SERVER['QUERY_STRING'];
    }
    header(sprintf("Location: %s", $updateGoTo));
}

mysqli_select_db($mlms, $database_mlms);
$query_updmemb = "SELECT memberId, fName, lName, phone, occupation, email, address, county, photo FROM member";
$updmemb = mysqli_query($mlms, $query_updmemb) or die(mysql_error());
$row_updmemb = mysqli_fetch_assoc($updmemb);
$totalRows_updmemb = mysqli_num_rows($updmemb);
?>
<html>
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
                        <td nowrap="nowrap" align="right" style="text-align: left;">MemberId:</td>
                        <td style="text-align: left;">
                            <?php echo $row_updmemb['memberId']; ?>
                        </td>
                    </tr>
                    <tr valign="baseline">
                        <td nowrap="nowrap" align="right" style="text-align: left;">FName:</td>
                        <td><input type="text" name="fName" readonly
                                value="<?php echo htmlentities($row_updmemb['fName'], ENT_COMPAT, 'utf-8'); ?>"
                                size="32" /></td>
                    </tr>
                    <tr valign="baseline">
                        <td nowrap="nowrap" align="right" style="text-align: left;">LName:</td>
                        <td><input type="text" name="lName" readonly
                                value="<?php echo htmlentities($row_updmemb['lName'], ENT_COMPAT, 'utf-8'); ?>"
                                size="32" /></td>
                    </tr>
                    <tr valign="baseline">
                        <td nowrap="nowrap" align="right" style="text-align: left;">Phone:</td>
                        <td><input type="text" name="phone" required
                                value="<?php echo htmlentities($row_updmemb['phone'], ENT_COMPAT, 'utf-8'); ?>"
                                size="32" /></td>
                    </tr>
                    <tr valign="baseline">
                        <td nowrap="nowrap" align="right" style="text-align: left;">Occupation:</td>
                        <td><input type="text" name="occupation" required
                                value="<?php echo htmlentities($row_updmemb['occupation'], ENT_COMPAT, 'utf-8'); ?>"
                                size="32" /></td>
                    </tr>
                    <tr valign="baseline">
                        <td nowrap="nowrap" align="right" style="text-align: left;">Email:</td>
                        <td><input type="text" name="email" required
                                value="<?php echo htmlentities($row_updmemb['email'], ENT_COMPAT, 'utf-8'); ?>"
                                size="32" /></td>
                    </tr>
                    <tr valign="baseline">
                        <td nowrap="nowrap" align="right" style="text-align: left;">Address:</td>
                        <td><input type="text" name="address" required
                                value="<?php echo htmlentities($row_updmemb['address'], ENT_COMPAT, 'utf-8'); ?>"
                                size="32" /></td>
                    </tr>
                    <tr valign="baseline">
                        <td nowrap="nowrap" align="right" style="text-align: left;">County:</td>
                        <td><input type="text" name="county" readonly
                                value="<?php echo htmlentities($row_updmemb['county'], ENT_COMPAT, 'utf-8'); ?>"
                                size="32" /></td>
                    </tr>
                    <tr valign="baseline">
                        <td nowrap="nowrap" align="right" style="text-align: left;">Photo:</td>
                        <td><input type="file" name="photo" required
                                value="<?php echo htmlentities($row_updmemb['photo'], ENT_COMPAT, 'utf-8'); ?>"
                                size="32" /></td>
                    </tr>
                    <tr valign="baseline">
                        <td nowrap="nowrap" align="right">&nbsp;</td>
                        <td><input class="frmBtn" type="submit" value="Update" /></td>
                    </tr>
                </table>
                <input type="hidden" name="MM_update" value="form1" />
                <input type="hidden" name="memberId" value="<?php echo $row_updmemb['memberId']; ?>" />
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
mysqli_free_result($updmemb);
?>