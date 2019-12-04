<?php
include '../config.php';

$object = $_GET['object'];
$value = $_GET['value'];
$userId = $_SESSION['user']['id'];

deleteReaction($object, $userId);
addReaction($object, $userId, $value);

$sqlReactions = "SELECT * FROM reaction WHERE product_id = '".$object."'";
$dataReactions = mysql_query($sqlReactions);
$dbReactions = array();

while ($rowReaction = mysql_fetch_assoc($dataReactions)) {
    $sqlReactionUser = "SELECT * FROM liste_user WHERE id = '".$rowReaction['user_id']."'";
    $dataReactionUser = mysql_query($sqlReactionUser);

    $rowReaction['user'] = mysql_fetch_assoc($dataReactionUser);
    $dbReactions[] = $rowReaction;
}

$objectId = $object;
$object = array();

$object['id'] = $objectId;
$object['reactions'] = array();

foreach($dbReactions as $reaction) {
    $object['reactions'][$reaction['type']][] = $reaction;
}

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

?>

<a href="#" data-reaction-list class="reaction-list">
    <?php foreach($object['reactions'] as $k => $reaction): ?>
        <div class="reaction">
            <img src="img/reaction/<?php echo $k; ?>.png" alt="">
            <span><?php echo count($object['reactions'][$k]); ?></span>
        </div>
    <?php endforeach; ?>

    <?php if (isset($_SESSION['user'])): ?>
        <?php if (0 === count($object['reactions'])): ?>
            <div class="reaction react-grey">
                <img src="img/reaction/2.png" alt="">
            </div>
        <?php endif; ?>
    <?php endif; ?>
</a>

<?php if (isset($_SESSION['user'])): ?>
    <div data-reaction-detail class="reaction-details" style="">
        <ul class="reaction-choices">
            <?php for ($i = 1; $i < 7; $i++): ?>
                <li>
                    <a data-add-reaction href="actions/addReaction.php?object=<?php echo $object['id']; ?>&value=<?php echo $i; ?>">
                        <img src="img/reaction/<?php echo $i; ?>.png" alt="">
                    </a>
                </li>
            <?php endfor; ?>
        </ul>
    </div>
<?php endif; ?>
