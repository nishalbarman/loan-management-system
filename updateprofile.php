<?php require_once('Connections/mlms.php'); ?>
<?php

session_start();
if (!(isset($_SESSION['login']) && $_SESSION['login'] === true)) {
    header("location: ./index.php");
    exit;
}

function GetSQLValueString($thevalue)
{
    return '$thevalue';
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
    $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {


    $fname = $_POST['fName'];
    $lname = $_POST['lName'];
    $phone = $_POST['phone'];
    $occuption = $_POST['occupation'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $country = $_POST['county'];
    $memberId = $_POST['memberId'];

    $updateSQL =
        "UPDATE member SET fName='$fname', lName='$lname', phone='$phone', occupation='$occuption', email='$email', address='$address', county='$country' WHERE memberId=$memberId";


    mysqli_select_db($mlms, $database_mlms);
    $Result1 = mysqli_query($mlms, $updateSQL) or die(mysql_error());

    $updateGoTo = "updateprofile.php";
    if (isset($_SERVER['QUERY_STRING'])) {
        $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
        $updateGoTo .= $_SERVER['QUERY_STRING'];
    }
    header(sprintf("Location: %s", $updateGoTo));
}

mysqli_select_db($mlms, $database_mlms);
$query_updprof = "SELECT memberId, fName, lName, phone, occupation, email, address, county FROM member";
$updprof = mysqli_query($mlms, $query_updprof);
$row_updprof = mysqli_fetch_assoc($updprof);
$totalRows_updprof = mysqli_num_rows($updprof);
?>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Member</title>
    <link rel="stylesheet" type="text/css" href="Assets/css/style.css">
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
    <?php include 'header/user.php'; ?>
    <p></p>
    <center>
        <div class="mainn">
            <h3>Update profile</h3>
            <form action="<?php echo $editFormAction; ?>" method="post" class="form" name="form1" id="form1">
                <table align="center">
                    <tr valign="baseline">
                        <td nowrap="nowrap" align="left"><b>Member ID:<b></td>
                        <td>
                            <?php echo $row_updprof['memberId']; ?>
                        </td>
                        <td nowrap="nowrap" align="left"><b>First name:<b></td>
                        <td><input type="text" name="fName" readonly
                                value="<?php echo htmlentities($row_updprof['fName'], ENT_COMPAT, 'utf-8'); ?>"
                                size="32" /></td>
                    </tr>
                    <tr valign="baseline">
                        <td nowrap="nowrap" align="left"><b>Last name:&nbsp;&nbsp;<b></td>
                        <td><input type="text" name="lName" readonly
                                value="<?php echo htmlentities($row_updprof['lName'], ENT_COMPAT, 'utf-8'); ?>"
                                size="32" /></td>

                        <td nowrap="nowrap" align="left" t"><b>Phone:&nbsp;&nbsp;<b></td>
                        <td><input type="text" name="phone"
                                value="<?php echo htmlentities($row_updprof['phone'], ENT_COMPAT, 'utf-8'); ?>"
                                size="32" /></td>
                    </tr>
                    <tr valign="baseline">
                        <td nowrap="nowrap" align="left"><b>Occupation:&nbsp;&nbsp;<b></td>
                        <td><input type="text" name="occupation"
                                value="<?php echo htmlentities($row_updprof['occupation'], ENT_COMPAT, 'utf-8'); ?>"
                                size="32" /></td>

                        <td nowrap="nowrap" align="left"><b>Email:&nbsp;&nbsp;<b></td>
                        <td><input type="text" name="email"
                                value="<?php echo htmlentities($row_updprof['email'], ENT_COMPAT, 'utf-8'); ?>"
                                size="32" /></td>
                    </tr>
                    <tr valign="baseline">
                        <td nowrap="nowrap" align="left"><b>Address:&nbsp;&nbsp;<b></td>
                        <td><input type="text" name="address"
                                value="<?php echo htmlentities($row_updprof['address'], ENT_COMPAT, 'utf-8'); ?>"
                                size="32" /></td>

                        <td nowrap="nowrap" align="left"><b>County:&nbsp;&nbsp;<b></td>
                        <td><input type="text" name="county" readonly
                                value="<?php echo htmlentities($row_updprof['county'], ENT_COMPAT, 'utf-8'); ?>"
                                size="32" /></td>
                    </tr>
                    <!-- <tr valign="baseline">
                        <td nowrap="nowrap" align="left"><b>Photo:&nbsp;&nbsp;<b></td>
                        <td><input type="file" name="photo" required
                                value="<?php echo htmlentities($row_updprof['photo'], ENT_COMPAT, 'utf-8'); ?>"
                                size="32" /></td>
                    </tr> -->
                </table>
                <br>
                <input class="frmBtn" type="submit" value="Update profile" />
                <input type="hidden" name="MM_update" value="form1" />
                <input type="hidden" name="memberId" value="<?php echo $row_updprof['memberId']; ?>" />
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
mysqli_free_result($updprof);
?>