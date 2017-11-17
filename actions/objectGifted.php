<?php
    include '../config.php';

    $objectId = $_GET['id'];
    editObject($objectId, $_SESSION['user']['id']);

    header('Location: ../index.php?user='.$_GET['friendId']);

function editObject($objectId, $userId)
{
    $query = "UPDATE `liste_noel` SET `gifted_by` = '$userId' WHERE `id` = $objectId";

    return mysql_query($query);
}
