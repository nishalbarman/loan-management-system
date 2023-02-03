<?php require_once('../Connections/mlms.php');

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
    $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form")) {
    $loantype = $_POST['loanType'];
    $loandesc = $_POST['description'];

    $insertSQL = "INSERT INTO loantype (`loanType`, `description`) VALUES ('$loantype', '$loandesc')";


    mysqli_select_db($mlms, $database_mlms);
    $Result1 = mysqli_query($mlms, $insertSQL) or die(mysql_error());

    $insertGoTo = "addloantype.php";
    if (isset($_SERVER['QUERY_STRING'])) {
        $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
        $insertGoTo .= $_SERVER['QUERY_STRING'];
    }
    header(sprintf("Location: %s", $insertGoTo));
}
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
        <div class="mainn">
            <div class="container">
                <h3>Add loan type</h3>
                <form name="form" METHOD="POST" action="<?php echo $editFormAction; ?>" class="fom">
                    <table align="center">
                        <tr>
                            <td style="text-align: left;">Loan type :</td>
                            <td style="text-align: left;"><input style="min-width: 100%;" type="text"
                                    placeholder="Enter loan type" name="loanType" required></td>
                        </tr>
                        <tr>
                            <td style="text-align: left;">Description :</td>
                            <td style="text-align: left;"><textarea style="min-width: 100%; height: 50px;"
                                    name="description" cols="" rows="" placeholder="Enter description"
                                    required="required"></textarea></td>
                        </tr>
                    </table>
                    <br />
                    <input class="frmBtn" type="submit" value="Add" style="width: 75%;"><input class="frmBtn"
                        type="reset" value="Reset" style="width: 75%; background-color: red; color: white;">
                    <input type="hidden" name="MM_insert" value="form" />
                </form>
            </div>
        </div>
    </center>


    <script type="text/javascript">
    function openNav() {
        document.getElementById("mySidenav").style.width = "250px";
        //document.getElementById("header").style.marginLeft = "250px";
    }

    function closeNav() {
        document.getElementById("mySidenav").style.width = "0";
        //document.getElementById("header").style.marginLeft= "0";
    };
    </script>

</body>
</html>