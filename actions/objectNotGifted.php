<?php
    include '../config.php';

    $objectId = $_GET['id'];
    editObject($objectId);

    header('Location: ../index.php?user='.$_GET['friendId']);

function editObject($objectId)
{
    $query = "UPDATE `liste_noel` SET `gifted_by` = NULL WHERE `id` = $objectId";

    return mysql_query($query);
}
