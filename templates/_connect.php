<?php if (isset($_SESSION['user'])): ?>
    <div class="current-user">
        <div class="current-avatar" style="background-image:url('uploads/<?php echo $_SESSION['user']['id'].'/'.$_SESSION['user']['pictureFile']; ?>')"></div>
        <a href="index.php?user=<?php echo $_SESSION['user']['code']; ?>"><?php echo $_SESSION['user']['nom']; ?></a>
        <a href="actions/disconnect.php" onclick="signOut();"><i class="fa fa-power-off"></i></a>
    </div>

    <?php if (0 < count($_SESSION['user']['friends'])): ?>
        <ul class="current-user-friends">
            <?php foreach ($_SESSION['user']['friends'] as $friend): ?>
                <li>
                    <a href="index.php?user=<?php echo $friend['code']; ?>" data-toggle="tooltip" title="<?php echo $friend['nom']; ?>" class="friend-img" style="background-image: url('uploads/<?php echo $friend['id'].'/'.$friend['pictureFile']; ?>')" data-placement="right"></a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
<?php else: ?>
    <div class="current-user">
        <a href="#" class="link-connect" href="#" data-toggle="modal" data-target="#connectModal">
            <i class="fa fa-power-off"></i> Se connecter
        </a>
    </div>
<?php endif; ?>
