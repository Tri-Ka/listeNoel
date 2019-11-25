<?php
include '../config.php';

$object = $_GET['object'];
$value = $_GET['value'];
$userId = $_SESSION['user']['id'];

deleteReaction($object, $userId);
addReaction($object, $userId, $value);

function deleteReaction($objectId, $userId)
{
    $sql = "DELETE FROM reaction WHERE product_id = '".$objectId."' AND user_id = '".$userId."'";
    mysql_query($sql);

    $sql = "DELETE FROM notification WHERE product_id = '".$objectId."' AND author_id = '".$userId."' AND type = 3";
    mysql_query($sql);
}

function addReaction($objectId, $userId, $value)
{
    mysql_insert('reaction', array(
        'product_id' => $objectId,
        'user_id' => $userId,
        'type' => $value,
    ));

    mysql_insert('notification', array(
        'author_id' => $userId,
        'product_id' => $objectId,
        'type' => '3',
        'created_at' => date('Y-m-d H:i:s')
    ));
}

function mysql_insert($table, $inserts)
{
    $values = array_map('mysql_real_escape_string', array_values($inserts));
    $keys = array_keys($inserts);
    $query = 'INSERT INTO `'.$table.'` (`'.implode('`,`', $keys).'`) VALUES (\''.implode('\',\'', $values).'\')';

    return mysql_query($query);
}

header('Location: ../index.php?user='.$_SESSION['currentUserCode']);
?>
