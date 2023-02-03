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

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_dispmember = 10;
$pageNum_dispmember = 0;
if (isset($_GET['pageNum_dispmember'])) {
    $pageNum_dispmember = $_GET['pageNum_dispmember'];
}
$startRow_dispmember = $pageNum_dispmember * $maxRows_dispmember;

mysqli_select_db($mlms, $database_mlms);
$query_dispmember = "SELECT id, memberId, fName, lName, phone, occupation, email, address, county, regDate FROM member";
$query_limit_dispmember = sprintf("%s LIMIT %d, %d", $query_dispmember, $startRow_dispmember, $maxRows_dispmember);
$dispmember = mysqli_query($mlms, $query_limit_dispmember) or die(mysql_error());
$row_dispmember = mysqli_fetch_assoc($dispmember);
if (empty($row_dispmember)) {
    $row_dispmember = array("id" => "No Data Found", "memberId" => "No Data Found", "fName" => "No Data Found", "lName" => "No Data Found", "gender" => "No Data Found", "phone" => "No Data Found", "occupation" => "No Data Found", "email" => "No Data Found", "password" => "No Data Found", "address" => "No Data Found", "county" => "No Data Found", "district" => "No Data Found", "location" => "No Data Found", "photo" => "No Data Found", "dob" => "No Data Found", "regDate" => "No Data Found");
}

if (isset($_GET['totalRows_dispmember'])) {
    $totalRows_dispmember = $_GET['totalRows_dispmember'];
} else {
    $all_dispmember = mysqli_query($mlms, $query_dispmember);
    $totalRows_dispmember = mysqli_num_rows($all_dispmember);
}
$totalPages_dispmember = ceil($totalRows_dispmember / $maxRows_dispmember) - 1;

$queryString_dispmember = "";
if (!empty($_SERVER['QUERY_STRING'])) {
    $params = explode("&", $_SERVER['QUERY_STRING']);
    $newParams = array();
    foreach ($params as $param) {
        if (
            stristr($param, "pageNum_dispmember") == false &&
            stristr($param, "totalRows_dispmember") == false
        ) {
            array_push($newParams, $param);
        }
    }
    if (count($newParams) != 0) {
        $queryString_dispmember = "&" . htmlentities(implode("&", $newParams));
    }
}
$queryString_dispmember = sprintf("&totalRows_dispmember=%d%s", $totalRows_dispmember, $queryString_dispmember);
?>

<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Admin</title>
    <link rel="stylesheet" type="text/css" href="../Assets/css/style.css">

</head>
<body>
    <?php include '../header/admin.php'; ?>
    <p></p>
    <center>
        <div class="main">
            <div class="container">
                <!-- <script>
                function myFunction() {
                    var input, filter, table, tr, td, i;
                    input = document.getElementById("myInput");
                    filter = input.value.toUpperCase();
                    table = document.getElementById("id01");
                    tr = table.getElementsByTagName("tr");
                    for (i = 0; i < tr.length; i++) {
                        td = tr[i].getElementsByTagName("td")[0];
                        if (td) {
                            if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
                                tr[i].style.display = "";
                            } else {

                                tr[i].style.display = "none";

                            }
                        }
                    }
                }
                </script> -->
                <h3 class="llTl">All members</h3>
                <table border="0" align="center">
                    <!-- Search: <input type="text" placeholder="search By ID/passport No..." id="myInput"
                        onKeyUp="myFunction()"><br><br> -->
                    <table align="center" id="id01" class="responsive-table">
                        <tr bgcolor="#33CCFF">
                            <td>#</td>
                            <td>Membr ID</td>
                            <td>First name</td>
                            <td>Last name</td>
                            <td>Phone</td>
                            <td>Occupation</td>
                            <td>Email</td>
                            <td>Address</td>
                            <td>County</td>
                            <td>Registration date</td>
                            <td>Update</td>
                            <td>Delete</td>
                        </tr>

                        <?php
                        do { ?>

                            <tr>
                                <td><a href="managemember.php?recordID=<?php echo $row_dispmember['id']; ?>">
                                        <?php echo $row_dispmember['id']; ?>&nbsp; </a></td>
                                <td>
                                    <?php echo $row_dispmember['memberId']; ?>&nbsp;
                                </td>
                                <td>
                                    <?php echo $row_dispmember['fName']; ?>&nbsp;
                                </td>
                                <td>
                                    <?php echo $row_dispmember['lName']; ?>&nbsp;
                                </td>
                                <td>
                                    <?php echo $row_dispmember['phone']; ?>&nbsp;
                                </td>
                                <td>
                                    <?php echo $row_dispmember['occupation']; ?>&nbsp;
                                </td>
                                <td>
                                    <?php echo $row_dispmember['email']; ?>&nbsp;
                                </td>
                                <td>
                                    <?php echo $row_dispmember['address']; ?>&nbsp;
                                </td>
                                <td>
                                    <?php echo $row_dispmember['county']; ?>&nbsp;
                                </td>
                                <td>
                                    <?php echo $row_dispmember['regDate']; ?>&nbsp;
                                </td>
                                <td><a href="updatemember.php?memberId=<?php echo $row_dispmember['memberId']; ?>"
                                        style="text-decoration:none">
                                        <font color="#0033FF">EDIT</font>
                                    </a></td>
                                <td><a href="deletemember.php?memberId=<?php echo $row_dispmember['memberId']; ?>"
                                        style="text-decoration:none">
                                        <font color="#FF0000">DELETE</font>
                                    </a></td>
                            </tr>
                        <?php } while ($row_dispmember = mysqli_fetch_assoc($dispmember)); ?>

                    </table>
                    Records
                    <?php echo ($startRow_dispmember + 1) ?> to
                    <?php echo min($startRow_dispmember + $maxRows_dispmember, $totalRows_dispmember) ?> of
                    <?php echo $totalRows_dispmember ?>
                    <br />
                    <table border="0">
                        <tr>
                            <td>
                                <?php if ($pageNum_dispmember > 0) { // Show if not first page ?>
                                    <a
                                        href="<?php printf("%s?pageNum_dispmember=%d%s", $currentPage, 0, $queryString_dispmember); ?>">First</a>
                                <?php } // Show if not first page ?>
                            </td>
                            <td>
                                <?php if ($pageNum_dispmember > 0) { // Show if not first page ?>
                                    <a
                                        href="<?php printf("%s?pageNum_dispmember=%d%s", $currentPage, max(0, $pageNum_dispmember - 1), $queryString_dispmember); ?>">Previous</a>
                                <?php } // Show if not first page ?>
                            </td>
                            <td>
                                <?php if ($pageNum_dispmember < $totalPages_dispmember) { // Show if not last page ?>
                                    <a
                                        href="<?php printf("%s?pageNum_dispmember=%d%s", $currentPage, min($totalPages_dispmember, $pageNum_dispmember + 1), $queryString_dispmember); ?>">Next</a>
                                <?php } // Show if not last page ?>
                            </td>
                            <td>
                                <?php if ($pageNum_dispmember < $totalPages_dispmember) { // Show if not last page ?>
                                    <a
                                        href="<?php printf("%s?pageNum_dispmember=%d%s", $currentPage, $totalPages_dispmember, $queryString_dispmember); ?>">Last</a>
                                <?php } // Show if not last page ?>
                            </td>
                        </tr>
                    </table>
            </div>
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
mysqli_free_result($dispmember);
?>