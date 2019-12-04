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

    $fileSize = $_FILES['pictureFile']['size'];
    $fileSize = round($fileSize / 1024 / 1024, 1);

if (3 < $fileSize) {
    $_SESSION['error'] = 'L\'image ne doit pas dépasser 3Mo';
    header('Location: ../index.php');
    exit;
}

if ($password !== $rePassword) {
    $_SESSION['error'] = 'les mots de passes sont différents';
    header('Location: ../index.php');
    exit;
}

if ('' !== trim($nom) &&
        '' !== trim($password)
    ) {
    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES['pictureFile']['tmp_name']);

    if ($check !== false) {
        $uploadOk = 1;
    } else {
        $_SESSION['error'] = 'Le fichier n\'est pas une image';
        header('Location: ../index.php');
        exit;
    }

    $result = mysql_insert('liste_user', array(
        'nom' => $nom,
        'code' => $code,
        'password' => trim(md5($password)),
        'theme' => 'noel',
        'pictureFile' => $_FILES['pictureFile']['name'],
    ));

    $user = retrieveUser($nom);

    $_SESSION['user'] = $user;

    $target_dir = '../uploads/'.$user['id'].'/';

    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777);
    }

    $target_file = $target_dir.basename($_FILES['pictureFile']['name']);
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

    if ($uploadOk) {
        if (move_uploaded_file($_FILES['pictureFile']['tmp_name'], $target_file)) {
            correctImageOrientation($target_file);
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

function correctImageOrientation($filename)
{
    if (exif_imagetype($filename) === IMAGETYPE_JPEG) {
        if (function_exists('exif_read_data')) {
            $exif = exif_read_data($filename);
            if ($exif && isset($exif['Orientation'])) {
                $orientation = $exif['Orientation'];

                if ($orientation != 1) {
                    $img = imagecreatefromjpeg($filename);
                    $deg = 0;

                    switch ($orientation) {
                        case 3:
                            $deg = 180;
                            break;
                        case 6:
                            $deg = 270;
                            break;
                        case 8:
                            $deg = 90;
                            break;
                    }

                    if ($deg) {
                        $img = imagerotate($img, $deg, 0);
                    }

                    // then rewrite the rotated image back to the disk as $filename
                    imagejpeg($img, $filename, 95);
                } // if there is some rotation necessary
            } // if have the exif orientation info
        } // if function exists
    }
}
