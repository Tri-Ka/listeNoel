<div class="text-center header" style="margin-bottom: 30px;">
    <div class="row">
        <div class="col-xs-12 col-md-3 col-sm-6 col-sm-offset-3 col-md-offset-0">
            <?php if ($currentUser): ?>
                <div class="avatar" width="150px" style="background-image: url('uploads/<?php echo $currentUser['id'].'/'.$currentUser['pictureFile']; ?>')">
                    <?php if (isset($_SESSION['user']) && $_SESSION['user']['id'] !== $currentUser['id']): ?>
                        <?php if (!in_array($currentUser, $_SESSION['user']['friends'])): ?>
                            <a href="actions/addFriend.php?friendCode=<?php echo $currentUser['code']; ?>" class="btn-add-friend" data-toggle="tooltip" title="ajouter cet ami"><i class="fa fa-user-plus"></i></a>
                        <?php else: ?>
                            <a href="actions/removeFriend.php?friendCode=<?php echo $currentUser['code']; ?>" class="btn-add-friend" data-toggle="tooltip" title="retirer cet ami"><i class="fa fa-user-times"></i></a>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['user']) && $_SESSION['user']['id'] === $currentUser['id']): ?>
                        <a href="#" class="btn-add-friend" data-toggle="modal" data-target="#editModal"><i class="fa fa-edit"></i></a>
                    <?php endif; ?>
                </div>

                <div class="name">
                    <?php echo $currentUser['nom']; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="col-xs-12 col-md-4 col-md-offset-1">
            <h1>
                <img src="img/<?php echo $currentTheme['label']; ?>/title.png" title="Ma Liste de Noël">
            </h1>
        </div>

        <div class="col-xs-12">
            <img class="img-head" src="img/<?php echo $currentTheme['label']; ?>/head.png" width="350px">
        </div>
    </div>
</div>
