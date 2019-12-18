<?php
    include 'config.php';

    if (isset($_COOKIE['listeKdoUserCode'])) {
        if (retrieveUser($_COOKIE['listeKdoUserCode'])) {
            $_SESSION['user'] = retrieveUser($_COOKIE['listeKdoUserCode']);
            setcookie('listeKdoUserCode', $_SESSION['user']['code'], time()+31556926 ,'/');
        }
    }

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
        $sql = "SELECT * FROM liste_noel WHERE user_id = '".$currentUser['id']."' ORDER BY created_at DESC, id DESC";
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

            $sqlReactions = "SELECT * FROM reaction WHERE product_id = '".$object['id']."'";
            $dataReactions = mysql_query($sqlReactions);
            $dbReactions = array();

            while ($rowReaction = mysql_fetch_assoc($dataReactions)) {
                $sqlReactionUser = "SELECT * FROM liste_user WHERE id = '".$rowReaction['user_id']."'";
                $dataReactionUser = mysql_query($sqlReactionUser);

                $rowReaction['user'] = mysql_fetch_assoc($dataReactionUser);
                $dbReactions[] = $rowReaction;
            }

            $object['reactions'] = array();

            foreach($dbReactions as $reaction) {
                $object['reactions'][$reaction['type']][] = $reaction;
            }

            if (null != $object['gifted_by']) {
                $dataUser = array();
                $sqlUser = "SELECT * FROM liste_user WHERE id = '".$object['gifted_by']."'";
                $queryUser = mysql_query($sqlUser);
                $object['gifted_by_datas'] = mysql_fetch_assoc($queryUser);
            }

            $objects[$object['id']] = $object;
        }
    }

    if (isset($_SESSION['user']) && null !== $_SESSION['user']) {
        $_SESSION['user']['friends'] = retrieveFriends($_SESSION['user']['id']);
        $_SESSION['user']['notifications'] = retrieveNotifications($_SESSION['user']);
    }

    $_SESSION['currentUserCode'] = $userId;

    function retrieveNotifications($user)
    {
        $notifications = array();

        if (0 === count($user['friends'])) {
            return array();
        }

        $sqlUser = "SELECT * FROM liste_user WHERE id = '".$user['id']."'";
        $dataUser = mysql_query($sqlUser);
        $datasUser = mysql_fetch_assoc($dataUser);

        $_SESSION['user']['last_seen_notif'] = $datasUser['last_seen_notif'];

        foreach ($user['friends'] as $friend) {
            $friendUserIds[] = $friend['id'];
        }

        $sql = "SELECT id FROM liste_noel WHERE user_id = '".$user['id']."'";
        $datas = mysql_query($sql);

        $productUserIds = array();

        while ($row = mysql_fetch_assoc($datas)) {
            $productUserIds[] = $row['id'];
        }

        $sql = "SELECT id FROM liste_noel WHERE user_id IN (".implode(', ', array_values($friendUserIds)).")";
        $datas = mysql_query($sql);

        while ($row = mysql_fetch_assoc($datas)) {
            $productFriendIds[] = $row['id'];
        }

        $moreSql = '';

        if (0 < count($productFriendIds)) {
            $moreSql = " OR (author_id != '".$user['id']."' AND author_id IN (".implode(', ', array_values($friendUserIds)).") AND type = 1 AND product_id IN (".implode(', ', array_values($productFriendIds)).")) ";
        }

        $sql = "SELECT * FROM notification WHERE ";

        if (0 < count($productUserIds)) {
            $sql .= " (author_id != '".$user['id']."' AND product_id IN (".implode(', ', array_values($productUserIds)).")) OR ";
        }
        
        $sql .= " (author_id != '".$user['id']."' AND author_id IN (".implode(', ', array_values($friendUserIds)).") AND type = 2) ";
        $sql .= $moreSql;
        $sql .= " ORDER BY created_at DESC LIMIT 100";

        $datas = mysql_query($sql);

        if ($datas) {
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

                $d1 = date('Y-m-d H:i:s');
                $d2 = $row['created_at'];
                $diff = cc2_date_diff($d1, $d2);

                if (0 !== $diff['d']) {
                    $row['timePassed'] = $diff['d'].' j';
                } elseif (0 !== $diff['h']) {
                    $row['timePassed'] = $diff['h'].' h';
                } elseif (0 !== $diff['m']) {
                    $row['timePassed'] = $diff['m'].' min';
                } elseif (0 !== $diff['s']) {
                    $row['timePassed'] = $diff['s'].' sec';
                }

                $notifications[] = $row;
            }
        }

        return $notifications;
    }

    function retrieveUser($code = null, $id = null)
    {
        $sql = null;
        
        if (null !== $code) {
            $sql = "SELECT * FROM liste_user WHERE code = '".$code."'";
        }

        if (null !== $id) {
            $sql = "SELECT * FROM liste_user WHERE id = '".$id."'";
        }

        if ($sql) {
            return mysql_fetch_assoc(mysql_query($sql));
        }

        return null;
    }

    function retrieveFriends($userId)
    {
        $sql = "SELECT * FROM liste_user WHERE liste_user.code IN (
            SELECT user_friend.friend_code FROM user_friend WHERE user_friend.user_id = '".$userId."'
        ) ORDER BY liste_user.nom ASC";

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

    function cc2_date_diff($start, $end)
    {
        $sdate = strtotime($start);
        $edate = strtotime($end);

        if ($edate < $sdate) {
            $sdate_temp = $sdate;
            $sdate = $edate;
            $edate = $sdate_temp;
        }
        $time = $edate - $sdate;
        $preday[0] = 0;

        $diff = array(
            's' => 0,
            'm' => 0,
            'h' => 0,
            'd' => 0,
        );

        if ($time>=0 && $time<=59) {
            // Seconds
            $timeshift = $time.' seconds ';
            $diff['s'] = $time;
        } elseif ($time>=60 && $time<=3599) {
            // Minutes + Seconds
            $pmin = ($edate - $sdate) / 60;
            $premin = explode('.', $pmin);

            $presec = $pmin-$premin[0];
            $sec = $presec*60;

            $timeshift = $premin[0].' min '.round($sec, 0).' sec ';
            $diff['m'] = $premin[0];
            $diff['s'] = round($sec, 0);
        } elseif ($time>=3600 && $time<=86399) {
            // Hours + Minutes
            $phour = ($edate - $sdate) / 3600;
            $prehour = explode('.', $phour);

            $premin = $phour-$prehour[0];
            $min = explode('.', $premin*60);

            if (!isset($min[1])) {
                $min[1] = 0;
            }

            $presec = '0.'.$min[1];
            $sec = $presec*60;

            $timeshift = $prehour[0].' hrs '.$min[0].' min '.round($sec, 0).' sec ';
            $diff['h'] = $prehour[0];
            $diff['m'] = $min[0];
            $diff['s'] = round($sec, 0);
        } elseif ($time>=86400) {
            // Days + Hours + Minutes
            $pday = ($edate - $sdate) / 86400;
            $preday = explode('.', $pday);

            $phour = $pday-$preday[0];
            $prehour = explode('.', $phour*24);

            $premin = ($phour*24)-$prehour[0];
            $min = explode('.', $premin*60);

            if (!isset($min[1])) {
                $min[1] = 0;
            }

            $presec = '0.'.$min[1];
            $sec = $presec*60;

            $timeshift = $preday[0].' days '.$prehour[0].' hrs '.$min[0].' min '.round($sec, 0).' sec ';
            $diff['d'] = $preday[0];
            $diff['h'] = $prehour[0];
            $diff['m'] = $min[0];
            $diff['s'] = round($sec, 0);
        }

        return $diff;
    }


    function retrieveAvatarUrl($userId = null, $userCode = null)
    {
        $user = retrieveUser($userCode, $userId);

        if (null !== $user['pictureFileUrl'] && null == $user['pictureFile']) {
            $pictureUrl = $user['pictureFileUrl'];
        } else {
            $pictureUrl = 'uploads/'.$user['id'].'/'.$user['pictureFile'];
        }

        return "background-image:url(".$pictureUrl.")";
    }
