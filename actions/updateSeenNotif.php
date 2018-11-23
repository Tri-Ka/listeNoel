<?php
include '../config.php';

$date = date('Y-m-d H:i:s');

$sql = 'UPDATE liste_user SET ';
$sql .= "last_seen_notif = '" . $date ."' ";
$sql .= "WHERE id = '".$_SESSION['user']['id'] ."'";

return mysql_query($sql);
