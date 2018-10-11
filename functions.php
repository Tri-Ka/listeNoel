<?php
    include 'config.php';

    $userId = null;
    $currentUser = null;
    $currentTheme = array(
        'label' => 'noel',
        'title' => 'Liste de Noël',
    );

    $themes = array(
        'noel' => 'Noël',
        'birthday' => 'Anniversaire',
        'naissance' => 'Naissance',
    );

    if (isset($_GET['user'])) {
        $userId = $_GET['user'];
    }

    if (null === $userId && isset($_SESSION['user'])) {
        $userId = $_SESSION['user']['code'];
    }

    if (retrieveUser($userId)) {
        $currentUser = retrieveUser($userId);
        $currentTheme['label'] = $currentUser['theme'];

        switch ($currentUser['theme']) {
            case 'noel':
                $currentTheme['title'] = 'Liste de Noël';
                break;

            case 'birthday':
                $currentTheme['title'] = 'Liste d\'anniversaire';
                break;

            case 'naissance':
                $currentTheme['title'] = 'Liste de naissance';
                break;

            default:
                # code...
                break;
        }
    }

    if ($currentUser) {
        $sql = "SELECT * FROM liste_noel WHERE user_id = '".$currentUser['id']."'";
        $datas = mysql_query($sql);

        $dbobjects = array();

        while ($row = mysql_fetch_assoc($datas)) {
            $dbobjects[] = $row;
        }

        $objects = array();

        foreach ($dbobjects as $object) {
            $object['description'] = str_replace('\"', '"', str_replace("\'", "'", $object['description']));
            $object['nom'] = str_replace('\"', '"', str_replace("\'", "'", $object['nom']));
            $objects[] = $object;
        }
    }

    if (isset($_SESSION['user']) && null !== $_SESSION['user']) {
        $_SESSION['user']['friends'] = retrieveFriends($_SESSION['user']['id']);
    }

    function retrieveUser($code)
    {
        $sql = "SELECT * FROM liste_user WHERE code = '".$code."'";

        return mysql_fetch_assoc(mysql_query($sql));
    }

    function retrieveFriends($userId)
    {
        $sql = "SELECT * FROM liste_user WHERE liste_user.code IN (
            SELECT user_friend.friend_code FROM user_friend WHERE user_friend.user_id = '".$userId."'
        )";

        $datas = mysql_query($sql);

        $friends = array();

        while ($row = mysql_fetch_assoc($datas)) {
            $friends[] = $row;
        }

        return $friends;
    }

    function auto_version($file)
    {
        if (!file_exists($_SERVER['DOCUMENT_ROOT'].'/listeNoel/'.$file)) {
            return $file;
        }

        $mtime = filemtime($_SERVER['DOCUMENT_ROOT'].'/listeNoel/'.$file);

        return $file.'?v='.$mtime;
    }
