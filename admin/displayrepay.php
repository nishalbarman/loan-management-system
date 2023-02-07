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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form")) {
    $tableName = $_POST['loanType'];
    $memberId = $_POST['memberId'];

    $pyId = $_POST['paymentId'];
    $sqlQuery = "UPDATE `" . $tableName . "` SET `status`='Paid' WHERE `id`=$pyId";
    $res = mysqli_query($mlms, $sqlQuery);

    $insertSQL = sprintf(
        "INSERT INTO payment (paymentId, memberId, fName, lName, amount, phone, payment_date, loanType) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
        GetSQLValueString($pyId, "text"),
        GetSQLValueString($memberId, "int"),
        GetSQLValueString($_POST['fName'], "text"),
        GetSQLValueString($_POST['lName'], "text"),
        GetSQLValueString($_POST['amount'], "int"),
        GetSQLValueString($_POST['phone'], "text"),
        GetSQLValueString($_POST['payment_date'], "date"),
        GetSQLValueString($_POST['loanType'], "text")
    );

    mysqli_select_db($mlms, $database_mlms);
    $Result1 = mysqli_query($mlms, $insertSQL) or die(mysqli_error($mlms));

    // $sql = "SELECT * FROM " . $tableName . " WHERE status='Unpaid'";
    // $res = mysqli_query($mlms, $sql);
    // $pyId = mysqli_num_rows($res);
    // // $pyId = $_POST['paymentId'];
    // $sqlQuery = "UPDATE " . $tableName . " SET `status`='Unpaid' WHERE `id`=$pyId";
    // $res = mysqli_query($mlms, $sqlQuery);

    $insertGoTo = "addpayment.php";
    if (isset($_SERVER['QUERY_STRING'])) {
        $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
        $insertGoTo .= $_SERVER['QUERY_STRING'];
    }
    echo "<script>alert('Payment Added'); window.location = '" . $insertGoTo . "'</script>";
    // header(sprintf("Location: %s", ));
}

$username = $_SESSION['MM_Username'];

?>

<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Admin</title>
<link rel="stylesheet" type="text/css" href="../Assets/css/style.css">
<style>
    td {
        font-size: 20px;
    }

    form {
        padding: 5px 30px 15px 30px;
        border: 1px solid greenyellow;
        display: inline-block;
        border-radius: 2px;
        width: 90%;
    }
</style>
</head>
<body>
    <?php include '../header/admin.php'; ?>
    <p></p>
    <center>
        <div class="main">
            <div class="container">
                <h3 class="llTl">Track Re-Payment
                </h3>
                <table>
                    <tr>
                        <td style=" text-align: left;">
                            <!-- <label for="memberId"><b>Member ID:</b></label> -->
                            <input type="text" placeholder="Enter Member id" pattern="[0-9]*" name="memberId" value=""
                                maxlength="20" onchange="populateLoan(this)">
                        </td>
                        <td></td>
                        <td id="selectionContaner" style="text-align: left; display: none;">
                            <!-- <label for="paymentId"><b>Select Loan:</b></label> -->
                            <div id="cont">

                            </div>
                        </td>
                    </tr>
                </table>

            </div>
            <div id="repayListTable">

            </div>

        </div>
        <script>
            function selectionFunction(target) {
                let value = target.value;
                console.log(target.value);
                fetch("../getRepayTable.php?table=" + value).then(res => res.json()).then(data => {
                    console.log(data);
                    const tableContainer = document.getElementById('repayListTable');

                    let table = document.createElement('table');
                    table.setAttribute("class", "responsive-table");
                    table.setAttribute("border", '1');
                    table.setAttribute("style",
                        'margin-top: 10px;border: 1px solid black;text-align: center;'
                    );
                    let array = ["ID", "Months", "Member ID", "Amount", "Loan type", "Status"]

                    let headerRow = document.createElement('tr');
                    headerRow.setAttribute("bgcolor",
                        "#33CCFF");
                    for (let j = 0; j < array.length; j++) {
                        let cell = document.createElement('th');
                        cell.innerHTML = array[j];
                        headerRow.appendChild(cell);
                    }

                    table.appendChild(headerRow);

                    for (let i = 0; i < data.length; i++) {
                        let row = document.createElement('tr');
                        for (let key in data[i]) {
                            let cell = document.createElement('td');
                            cell.innerHTML = data[i][key];
                            row.appendChild(cell);
                            row.setAttribute("style", "border: 1px solid #86bc25; text-align: center;");
                        }
                        table.appendChild(row);
                    }

                    tableContainer.appendChild(table);

                });

            }

            function populateLoan(target) {
                let value = target.value;
                fetch("getLoans.php?member=" + value).then(res => res.json()).then(data => {
                    const container = document.getElementById('cont');
                    container.innerHTML = "";
                    const select = document.createElement('select');
                    select.setAttribute("name", "loanType");
                    select.setAttribute("onchange", "selectionFunction(this)");
                    select.options[select.options.length] = new Option("--- Select Loan ---", "");


                    document.getElementById('selectionContaner').style.display = "";

                    data.forEach(element => {
                        console.log(element);
                        select.options[select.options.length] = new Option(element.loanName, element
                            .loanTableName);

                    });
                    container.appendChild(select);
                })
            }
        </script>

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