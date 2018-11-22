<?php
    include '../config.php';

    deleteComment($_GET['id']);


function deleteComment($id)
{
    $sql = "DELETE FROM comment WHERE id = '".$id."' AND user_id = '".$_SESSION['user']['id']."'";

    return mysql_query($sql);
}
