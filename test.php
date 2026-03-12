<?php
$conn = mysqli_connect(
"alchemis-mysql-upgrade.cswhqpuhwywg.eu-west-1.rds.amazonaws.com",
"alchemis",
"rYT4maP7",
"alchemis"
);

$result = mysqli_query($conn,"SELECT COUNT(*) as total FROM tbl_clients");

$row = mysqli_fetch_assoc($result);

print_r($row);
?>