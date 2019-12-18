<?php 

include '../config.php';

$id = $_POST['id'];
$name = $_POST['name'];
$imageUrl = $_POST['imageUrl'];
$email = $_POST['email'];            
$code = md5($id);

if (null != $id) {
    if (retrieveUser($id)) {
        $user = retrieveUser($id);
    } else {    
        mysql_insert('liste_user', array(
            'nom' => $name,
            'code' => $code,
            'theme' => 'noel',
            'googleId' => $id,
            'pictureFileUrl' => $imageUrl,
        ));
    }
    
    $_SESSION['user'] = $user;
    setcookie('listeKdoUserCode', $user['code'], time()+31556926 ,'/');
}

return;

function retrieveUser($googleId)
{
    $sql = "SELECT * FROM liste_user WHERE googleId = '".$googleId."'";

    return mysql_fetch_assoc(mysql_query($sql));
}

function mysql_insert($table, $inserts)
{
    $values = array_map('mysql_real_escape_string', array_values($inserts));
    $keys = array_keys($inserts);

    return mysql_query('INSERT INTO `'.$table.'` (`'.implode('`,`', $keys).'`) VALUES (\''.implode('\',\'', $values).'\')');
}
