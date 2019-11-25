<?php if (isset($_SESSION['user'])) : ?>
    <div class="notifications" data-notif>
        <a class="toggle-notif" href="actions/updateSeenNotif.php" data-toggle-notif>
            <i class="fa fa-bell"></i>
        </a>

        <ul class="notification-list">
            <?php foreach ($_SESSION['user']['notifications'] as $notification) : ?>
                <?php if ($notification['product_user']['code']) : ?>
                    <li class="notification <?php echo $notification['new'] ? 'new' : ''; ?>">
                        <a data-notif-link href="index.php?user=<?php echo $notification['product_user']['code']; ?>#idea-<?php echo $notification['product_id'];?>">
                            <div class="comment-avatar" style="background-image: url('uploads/<?php echo $notification['user']['id'] .'/'. $notification['user']['pictureFile']; ?>')"></div>

                            <div class="comment-content">
                                <?php if (1 == $notification['type']) : ?>
                                    <?php if ($notification['product']['user_id'] == $_SESSION['user']['id']) : ?>
                                        <span class="comment-user"><?php echo $notification['user']['nom']; ?></span> a commenté votre idée !
                                    <?php else : ?>
                                        <span class="comment-user"><?php echo $notification['user']['nom']; ?></span> a commenté une idée !
                                    <?php endif; ?>
                                <?php elseif (2 == $notification['type']) : ?>
                                    <span class="comment-user"><?php echo $notification['user']['nom']; ?></span> a ajouté une nouvelle idée !
                                <?php elseif (3 == $notification['type']) : ?>
                                    <span class="comment-user"><?php echo $notification['user']['nom']; ?></span> a réagit à une idée !
                                <?php endif; ?>
                            </div>

                            <div class="notif-date">
                                <?php echo $notification['timePassed']; ?>
                            </div>
                        </a>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
