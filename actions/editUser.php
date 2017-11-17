<?php
    include '../config.php';

    function editUser($user)
    {
        $sql = 'UPDATE liste_user SET ';
        $sql .= "nom = '".$user['nom']."', ";
        $sql .= "password = '".$user['password']."', ";
        $sql .= "pictureFile = '".$user['pictureFile']."' ";
        $sql .= "WHERE id = '".$_SESSION['user']['id']."'";

        return mysql_query($sql);
    }

    $nom = $_POST['nom'];
    $password = $_POST['password'];
    $rePassword = $_POST['re-password'];

    if ($password !== $rePassword) {
        $_SESSION['error'] = 'les mots de passes sont différents';
        header('Location: ../index.php');
        exit;
    }

    if (
        '' !== trim($nom) &&
        '' !== trim($password)
    ) {
        $result = editUser(array(
            'nom' => $nom,
            'password' => md5($password),
            'pictureFile' => $_FILES['pictureFile']['name'],
        ));

        $user = retrieveUser($nom);

        $target_dir = '../uploads/'.$user['id'].'/';

        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777);
        }

        $target_file = $target_dir.basename($_FILES['pictureFile']['name']);
        $uploadOk = 1;
        $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES['pictureFile']['tmp_name']);

        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $_SESSION['error'] = 'Les fichier n\'est pas une image';
            header('Location: ../index.php');
            exit;
        }

        if ($uploadOk) {
            if (move_uploaded_file($_FILES['pictureFile']['tmp_name'], $target_file)) {
                $pictureFile = $_FILES['pictureFile']['name'];
            } else {
                $_SESSION['error'] = 'erreur lors de l\'upload';
                header('Location: ../index.php');
                exit;
            }
        }

        $_SESSION['user'] = $user;

        header('Location: ../index.php?user='.$user['code']);
    } else {
        $_SESSION['error'] = 'des champs requis ne sont pas renseignés';
        header('Location: ../index.php');
    }

    function retrieveUser($username)
    {
        $sql = "SELECT * FROM liste_user WHERE nom = '".$username."'";

        return mysql_fetch_assoc(mysql_query($sql));
    }
