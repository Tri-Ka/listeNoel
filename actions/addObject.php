<?php
include '../config.php';

$nom = $_POST['nom'];
$description = $_POST['description'];
$imageUrl = $_POST['image'];
$file = $_FILES['file'];
$link = $_POST['link'];
$userId = $_POST['user_id'];
$fileName = null;

if (null != $file['name']) {
    $fileSize = $_FILES['file']['size'];
    $fileSize = round($fileSize / 1024 / 1024, 1);

    if (3 < $fileSize) {
        $_SESSION['error'] = 'L\'image ne doit pas dépasser 3Mo';
        header('Location: ../index.php');
        exit;
    }

    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES['file']['tmp_name']);

    if ($check !== false) {
        $uploadOk = 1;
    } else {
        $_SESSION['error'] = 'Le fichier n\'est pas une image';
        header('Location: ../index.php');
        exit;
    }

    $fileName = $_FILES['file']['name'];

    $target_dir = '../uploads/img/';

    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777);
    }

    $target_file = $target_dir.basename($_FILES['file']['name']);
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

    if ($uploadOk) {
        if (move_uploaded_file($_FILES['file']['tmp_name'], $target_file)) {
            $pictureFile = $_FILES['file']['name'];
        } else {
            $_SESSION['error'] = 'erreur lors de l\'upload';
            header('Location: ../index.php');
            exit;
        }
    }
}

if ('' !== $nom && '' !== $description) {
    mysql_insert('liste_noel', array(
        'nom' => $nom,
        'description' => $description,
        'image_url' => $imageUrl,
        'link' => $link,
        'user_id' => $userId,
        'file' => $fileName,
    ));
}

header('Location: ../index.php?user='.$_SESSION['user']['code']);

function mysql_insert($table, $inserts)
{
    $values = array_map('mysql_real_escape_string', array_values($inserts));
    $keys = array_keys($inserts);
    $query = 'INSERT INTO `'.$table.'` (`'.implode('`,`', $keys).'`) VALUES (\''.implode('\',\'', $values).'\')';

    return mysql_query($query);
}
