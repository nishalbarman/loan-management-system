<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_mlms = "localhost";
$database_mlms = "lms";
$username_mlms = "root";
$password_mlms = "";

$mlms = mysqli_connect($hostname_mlms, $username_mlms, $password_mlms, $database_mlms) or die(mysql_error());
?>