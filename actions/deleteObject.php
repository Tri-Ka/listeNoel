<?php
    include '../config.php';

    deleteObject($_GET['id']);

    header('Location: ../index.php');
    exit;

function deleteObject($objectId)
{
    $sql = "DELETE FROM notification WHERE product_id = '".$objectId;

    mysql_query($sql);

    $sql = "DELETE FROM liste_noel WHERE id = '".$objectId."' AND user_id = '".$_SESSION['user']['id']."'";

    return mysql_query($sql);
}
