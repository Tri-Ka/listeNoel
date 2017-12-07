<?php
    include '../config.php';

    function editObject($object)
    {
        $sql = 'UPDATE liste_noel SET ';
        $sql .= "nom = '".$object['nom']."', ";
        $sql .= "description = '".$object['description']."', ";
        $sql .= "image_url = '".$object['image_url']."', ";
        $sql .= "link = '".$object['link']."' ";
        $sql .= "WHERE user_id = '".$_SESSION['user']['id']."' AND id = '".$object['id']."'";

        return mysql_query($sql);
    }

    $id = $_POST['object_id'];
    $nom = $_POST['nom'];
    $description = $_POST['description'];
    $imageUrl = $_POST['image'];
    $link = $_POST['link'];

    if ('' !== $nom && '' !== $description) {
        editObject(array(
            'nom' => $nom,
            'description' => $description,
            'image_url' => $imageUrl,
            'link' => $link,
            'id' => $id
        ));
    }

    header('Location: ../index.php?user='.$_SESSION['user']['code']);
