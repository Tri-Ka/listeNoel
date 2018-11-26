<?php
    include '../config.php';

function editObject($object)
{
    $sql = 'UPDATE liste_noel SET ';
    $sql .= "nom = '".$object['nom']."', ";
    $sql .= "description = '".$object['description']."', ";
    $sql .= "image_url = '".$object['image_url']."', ";
    $sql .= "file = '".$object['file']."', ";
    $sql .= "link = '".$object['link']."' ";
    $sql .= "WHERE user_id = '".$_SESSION['user']['id']."' AND id = '".$object['id']."'";

    return mysql_query($sql);
}

    $id = $_POST['object_id'];
    $nom = $_POST['nom'];
    $description = $_POST['description'];
    $imageUrl = $_POST['image'];
    $link = $_POST['link'];
    $file = $_FILES['file'];
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

if ('' !== $nom) {
    editObject(array(
        'nom' => $nom,
        'description' => $description,
        'image_url' => $imageUrl,
        'link' => $link,
        'id' => $id,
        'file' => $fileName,
    ));
}

    header('Location: ../index.php?user='.$_SESSION['user']['code']);
