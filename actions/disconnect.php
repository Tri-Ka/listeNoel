<?php
    session_start();
    session_destroy();

    unset($_COOKIE['listeKdoUserCode']);
    setcookie("listeKdoUserCode", '', 1 ,'/');

    header('Location: ../index.php');
