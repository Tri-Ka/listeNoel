<?php
    include '../config.php';

    $nom = $_POST['nom'];
    $password = $_POST['password'];
    $rePassword = $_POST['re-password'];
    $code = md5($nom.$password);

    if (retrieveUser($nom)) {
        $_SESSION['error'] = 'Ce nom existe déjà';
        header('Location: ../index.php');
        exit;
    }

    if ($password !== $rePassword) {
        $_SESSION['error'] = 'les mots de passes sont différents';
        header('Location: ../index.php');
        exit;
    }

    if (
        '' !== trim($nom) &&
        '' !== trim($password)
    ) {
        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES['pictureFile']['tmp_name']);

        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $_SESSION['error'] = 'Les fichier n\'est pas une image';
            header('Location: ../index.php');
            exit;
        }

        $result = mysql_insert('liste_user', array(
            'nom' => $nom,
            'photo' => $photo,
            'code' => $code,
            'password' => trim(md5($password)),
            'theme' => 'noel',
            'pictureFile' => $_FILES['pictureFile']['name'],
        ));

        $user = retrieveUser($nom);

        $_SESSION['user'] = $user;

        $target_dir = '../uploads/'.$user['id'].'/';

        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $target_file = $target_dir.basename($_FILES['pictureFile']['name']);
        $uploadOk = 1;
        $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

        if ($uploadOk) {
            if (move_uploaded_file($_FILES['pictureFile']['tmp_name'], $target_file)) {
                $pictureFile = $_FILES['pictureFile']['name'];
            } else {
                $_SESSION['error'] = 'erreur lors de l\'upload';
                header('Location: ../index.php');
                exit;
            }
        }

        header('Location: ../index.php?user='.$code);
        exit;
    } else {
        $_SESSION['error'] = 'des champs requis ne sont pas renseignés';
        header('Location: ../index.php');
        exit;
    }

function mysql_insert($table, $inserts)
{
    $values = array_map('mysql_real_escape_string', array_values($inserts));
    $keys = array_keys($inserts);

    return mysql_query('INSERT INTO `'.$table.'` (`'.implode('`,`', $keys).'`) VALUES (\''.implode('\',\'', $values).'\')');
}

function retrieveUser($username)
{
    $sql = "SELECT * FROM liste_user WHERE nom = '".$username."'";

    return mysql_fetch_assoc(mysql_query($sql));
}
