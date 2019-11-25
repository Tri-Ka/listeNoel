<?php
    include '../config.php';

    function editUser($user)
    {
        $sql = 'UPDATE liste_user SET ';
        $sql .= "nom = '".$user['nom']."'";

        if (isset($user['password'])) {
            $sql .= " , password = '".$user['password']."'";
        }

        if (isset($user['pictureFile'])) {
            $sql .= " , pictureFile = '".$user['pictureFile']."'";
        }

        $sql .= " WHERE id = '".$_SESSION['user']['id']."'";

        return mysql_query($sql);
    }

    $nom = $_POST['nom'];
    $password = $_POST['password'];
    $rePassword = $_POST['re-password'];

    if ('' === trim($nom)) {
        $_SESSION['error'] = 'Le nom ne peut pas être vide';
        header('Location: ../index.php');
        exit;
    }

    if (0 < $_FILES['pictureFile']['size']) {
        $fileSize = $_FILES['pictureFile']['size'];
        $fileSize = round($fileSize / 1024 / 1024, 1);

        if (3 < $fileSize) {
            $_SESSION['error'] = 'L\'image ne doit pas dépasser 3Mo';
            header('Location: ../index.php');
            exit;
        }

        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES['pictureFile']['tmp_name']);

        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $_SESSION['error'] = 'Les fichier n\'est pas une image';
            header('Location: ../index.php');
            exit;
        }

        $target_dir = '../uploads/'.$_SESSION['user']['id'].'/';

        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777);
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
    }

    if ($password && $password !== $rePassword) {
        $_SESSION['error'] = 'les mots de passes sont différents';
        header('Location: ../index.php');
        exit;
    }

    $userInfos = array();
    $userInfos['nom'] = $nom;

    if ($password) {
        $userInfos['password'] = md5($password);
    }

    if (0 < $_FILES['pictureFile']['size']) {
        $userInfos['pictureFile'] = $_FILES['pictureFile']['name'];
    }

    $result = editUser($userInfos);
    $user = retrieveUser($nom);

    $_SESSION['user'] = $user;

    header('Location: ../index.php?user='.$user['code']);
  
    function retrieveUser($username)
    {
        $sql = "SELECT * FROM liste_user WHERE nom = '".$username."'";

        return mysql_fetch_assoc(mysql_query($sql));
    }
