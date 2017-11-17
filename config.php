<?php
    if (!isset($_SESSION)) {
        session_start();
    }

    $_host = 'sql.free.fr';
    $_db = 'datcharrye';
    $_username = 'datcharrye';
    $_pass = 'spx728';

    $_host = 'localhost';
    $_db = 'projet_noel';
    $_username = 'root';
    $_pass = 'root';

    $link = mysql_connect($_host, $_username, $_pass);
    $db_selected = mysql_select_db($_db);
