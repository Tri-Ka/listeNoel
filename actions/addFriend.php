<?php
    include '../config.php';

    $friendCode = $_GET['friendCode'];
    mysqlAddFriend($_SESSION['user']['id'], $friendCode);

    $friend = retrieveUser($friendCode);
    mysqlAddFriend($friend['id'], $_SESSION['user']['code']);

    header('Location: ../index.php?user='.$friendCode);

function mysqlAddFriend($userId, $friendCode)
{
    $query = "INSERT INTO `user_friend` (`user_id`, `friend_code`) VALUES ('".$userId."', '".$friendCode."')";

    return mysql_query($query);
}

function retrieveUser($friendCode)
{
    $sql = "SELECT * FROM liste_user WHERE code = '".$friendCode."'";

    return mysql_fetch_assoc(mysql_query($sql));
}
