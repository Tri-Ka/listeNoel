<?php
    include '../config.php';

    $nom = $_POST['nom'];
    $description = $_POST['description'];
    $imageUrl = $_POST['image'];
    $link = $_POST['link'];
    $userId = $_POST['user_id'];

    if ('' !== $nom && '' !== $description) {
        mysql_insert('liste_noel', array(
            'nom' => $nom,
            'description' => $description,
            'image_url' => $imageUrl,
            'link' => $link,
            'user_id' => $userId,
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
