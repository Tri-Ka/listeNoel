<?php
include '../config.php';

$content = $_POST['content'];
$productId = $_POST['productId'];
$userId = $_SESSION['user']['id'];

if ('' !== $content && '' !== $userId) {
    mysql_insert('comment', array(
        'content' => $content,
        'product_id' => $productId,
        'user_id' => $userId,
    ));

    $commentId = mysql_insert_id();

    mysql_insert('notification', array(
        'author_id' => $userId,
        'product_id' => $productId,
        'type' => '1',
        'created_at' => date('Y-m-d H:i:s')
    ));
}

$content = str_replace('\"', '"', str_replace("\'", "'", $content));

function mysql_insert($table, $inserts)
{
    $values = array_map('mysql_real_escape_string', array_values($inserts));
    $keys = array_keys($inserts);
    $query = 'INSERT INTO `'.$table.'` (`'.implode('`,`', $keys).'`) VALUES (\''.implode('\',\'', $values).'\')';

    return mysql_query($query);
}

?>

<li class="comment" data-comment>
    <div class="comment-avatar" style="background-image: url('uploads/<?php echo $_SESSION['user']['id'] .'/'. $_SESSION['user']['pictureFile']; ?>')"></div>

    <div class="comment-content">
        <span class="comment-user"><?php echo $_SESSION['user']['nom']; ?></span> <?php echo nl2br($content); ?>

        <a href="actions/deleteComment.php?id=<?php echo $commentId; ?>" class="delete-comment" data-delete-comment data-toggle="tooltip" data-original-title="supprimer mon commentaire">
            <i class="fa fa-trash"></i>
        </a>
    </div>
</li>
