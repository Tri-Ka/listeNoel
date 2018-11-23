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
            $object['description'] = trim(str_replace('\"', '"', str_replace("\'", "'", $object['description'])));
            $object['nom'] = str_replace('\"', '"', str_replace("\'", "'", $object['nom']));

            if (null != $object['file']) {
                $object['image_url'] = 'uploads/img/'.$object['file'];
            }

            $sqlComment = "SELECT * FROM comment WHERE product_id = '".$object['id']."'";
            $dataComments = mysql_query($sqlComment);
            $dbComments = array();

            while ($rowComment = mysql_fetch_assoc($dataComments)) {
                $sqlCommentUser = "SELECT * FROM liste_user WHERE id = '".$rowComment['user_id']."'";
                $dataCommentUser = mysql_query($sqlCommentUser);

                $rowComment['user'] = mysql_fetch_assoc($dataCommentUser);
                $rowComment['content'] = str_replace('\"', '"', str_replace("\'", "'", $rowComment['content']));
                $dbComments[] = $rowComment;
            }

            $object['comments'] = $dbComments;
            $objects[$object['id']] = $object;
        }
    }

    if (isset($_SESSION['user']) && null !== $_SESSION['user']) {
        $_SESSION['user']['friends'] = retrieveFriends($_SESSION['user']['id']);
        $_SESSION['user']['notifications'] = retrieveNotifications($_SESSION['user']);
    }

    function retrieveNotifications($user)
    {
        $sqlUser = "SELECT * FROM liste_user WHERE id = '".$user['id']."'";
        $dataUser = mysql_query($sqlUser);
        $datasUser = mysql_fetch_assoc($dataUser);

        $_SESSION['user']['last_seen_notif'] = $datasUser['last_seen_notif'];

        foreach ($user['friends'] as $friend) {
            $friendUserIds[] = $friend['id'];
        }

        $sql = "SELECT id FROM liste_noel WHERE user_id = '".$user['id']."'";
        $datas = mysql_query($sql);

        while ($row = mysql_fetch_assoc($datas)) {
            $productUserIds[] = $row['id'];
        }

        $sql = "SELECT id FROM liste_noel WHERE user_id IN (".implode(', ', array_values($friendUserIds)).")";
        $datas = mysql_query($sql);

        while ($row = mysql_fetch_assoc($datas)) {
            $productFriendIds[] = $row['id'];
        }

        $sql = "SELECT * FROM notification
        WHERE (author_id != '".$user['id']."' AND product_id IN (".implode(', ', array_values($productUserIds))."))
        OR (author_id != '".$user['id']."' AND author_id IN (".implode(', ', array_values($friendUserIds)).") AND type = 2)
        OR (author_id != '".$user['id']."' AND author_id IN (".implode(', ', array_values($friendUserIds)).") AND type = 1 AND product_id IN (".implode(', ', array_values($productFriendIds))."))
        ORDER BY created_at DESC";

        $datas = mysql_query($sql);

        $notifications = array();

        while ($row = mysql_fetch_assoc($datas)) {
            $sqlNotifUser = "SELECT * FROM liste_user WHERE id = '".$row['author_id']."'";
            $dataNotifUser = mysql_query($sqlNotifUser);
            $row['user'] = mysql_fetch_assoc($dataNotifUser);

            $sqlNotifProduct = "SELECT * FROM liste_noel WHERE id = '".$row['product_id']."'";
            $dataNotifProduct = mysql_query($sqlNotifProduct);
            $row['product'] = mysql_fetch_assoc($dataNotifProduct);

            $sqlNotifProductUser = "SELECT * FROM liste_user WHERE id = '".$row['product']['user_id']."'";
            $dataNotifProductUser = mysql_query($sqlNotifProductUser);
            $row['product_user'] = mysql_fetch_assoc($dataNotifProductUser);

            $row['new'] = false;

            if (null === $_SESSION['user']['last_seen_notif'] || strtotime($row['created_at']) > strtotime($_SESSION['user']['last_seen_notif'])) {
                $row['new'] = true;
            }

            $notifications[] = $row;
        }

        return $notifications;
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
        if (!file_exists($_SERVER['DOCUMENT_ROOT'].'/listeKdo/'.$file)) {
            return $file;
        }

        $mtime = filemtime($_SERVER['DOCUMENT_ROOT'].'/listeKdo/'.$file);

        return $file.'?v='.$mtime;
    }
