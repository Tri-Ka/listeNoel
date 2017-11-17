<?php
    include '../config.php';

    $friendCode = $_GET['friendCode'];
    removeFriend($_SESSION['user']['id'], $friendCode);

    $friend = retrieveUser($friendCode);
    removeFriend($friend['id'], $_SESSION['user']['code']);

    header('Location: ../index.php?user='.$friendCode);

function removeFriend($userId, $friendCode)
{
    $query = 'DELETE FROM `user_friend` WHERE `user_id` = '.$userId.' AND `friend_code` = "'.$friendCode.'"';

    return mysql_query($query);
}

function retrieveUser($friendCode)
{
    $sql = "SELECT * FROM liste_user WHERE code = '".$friendCode."'";

    return mysql_fetch_assoc(mysql_query($sql));
}
