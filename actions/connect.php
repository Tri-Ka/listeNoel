<?php
    include '../config.php';

    $nom = $_POST['nom'];
    $password = $_POST['password'];

    $user = retrieveUser($nom, $password);

    if ($user) {
        $_SESSION['user'] = $user;
        setcookie('listeKdoUserCode', $user['code'], time()+31556926 ,'/');

        header('Location: ../index.php?user='.$user['code']);
        exit;
    } else {
        $_SESSION['error'] = 'nom ou mot de passe invalide';
        header('Location: ../index.php');
        exit;
    }

function retrieveUser($username, $password)
{
    $sql = "SELECT * FROM liste_user WHERE nom = '".$username."' AND password = '".md5($password)."'";

    return mysql_fetch_assoc(mysql_query($sql));
}
