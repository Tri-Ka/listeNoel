<?php
    include '../config.php';

    changeTheme($_GET['theme']);

    header('Location: ../index.php');
    exit;

    function changeTheme($theme)
    {
        $sql = "UPDATE liste_user SET theme = '".$theme."' WHERE id = '".$_SESSION['user']['id']."'";

        return mysql_query($sql);
    }
