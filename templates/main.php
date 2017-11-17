<?php if (isset($_SESSION['user'])): ?>
    <div class="current-user">
        <div class="current-avatar" style="background-image:url('uploads/<?php echo $_SESSION['user']['id'].'/'.$_SESSION['user']['pictureFile']; ?>')"></div>
        <a href="index.php?user=<?php echo $_SESSION['user']['code']; ?>"><?php echo $_SESSION['user']['nom']; ?></a>
        <a href="actions/disconnect.php"><i class="fa fa-power-off"></i></a>
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

<?php if (isset($_SESSION['user']) && $_SESSION['user']['id'] === $currentUser['id']): ?>
    <div class="text-center marged-top">
        <select class="" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">
            <option>-- Sélectionner un thème --</option>
            <?php foreach ($themes as $key => $theme): ?>
                <option value="actions/changeTheme.php?theme=<?php echo $key; ?>"><?php echo $theme; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
<?php endif; ?>

<div class="container">
    <div class="bs-docs-section clearfix">
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

        <div class="row">
            <div class="col-xs-12">
                <div class="text-center">
                    <label class="marged-top">
                        Partager à vos amis
                    </label>

                    <div class="a2a_kit a2a_kit_size_32 share-btns">
                        <a class="a2a_button_facebook"></a>
                        <a class="a2a_button_twitter"></a>
                        <a class="a2a_button_google_plus"></a>
                    </div>

                    <span class="share-url">
                        http://datcharrye.free.fr/listeNoel/index.php?user=<?php echo $currentUser['code']; ?>
                    </span>
                </div>
            </div>
        </div>

        <?php if ($currentUser): ?>
            <div class="row">
                <div class="grid">
                    <?php foreach ($objects as $object): ?>
                        <div class="col-xs-12 col-sm-6 col-md-4 grid-item">
                            <div class="
                                panel panel-default panel-object
                                <?php if (isset($_SESSION['user']) && $_SESSION['user']['id'] !== $currentUser['id'] && null !== $object['gifted_by'] && $object['gifted_by'] !== $_SESSION['user']['id']): ?>
                                    gifted
                                <?php endif; ?>
                            ">
                                <?php if (isset($_SESSION['user']) && $_SESSION['user']['id'] === $currentUser['id']): ?>
                                    <a class="delete-obj" href="actions/deleteObject.php?id=<?php echo $object['id']; ?>" data-confirm="Êtes-vous sûr de vouloir supprimer cette belle idée ?">
                                        <i class="fa fa-times"></i>
                                    </a>
                                <?php endif; ?>

                                <div class="nom">
                                    <?php echo $object['nom']; ?>
                                </div>

                                <?php if ('' !== $object['image_url']): ?>
                                    <div class="img-container text-center">
                                        <img src="<?php echo $object['image_url']; ?>">
                                    </div>
                                <?php endif; ?>

                                <div class="panel-detail">
                                    <p>
                                        <?php echo nl2br(substr($object['description'], 0, 300)); ?> ...
                                    </p>

                                    <?php if (isset($_SESSION['user']) && $_SESSION['user']['id'] !== $currentUser['id']): ?>
                                        <?php if (null === $object['gifted_by']): ?>
                                            <div class="text-center">
                                                <a href="actions/objectGifted.php?id=<?php echo $object['id']; ?>&friendId=<?php echo $currentUser['code']; ?>" class="btn btn-primary">
                                                    <i class="fa fa-gift"></i> J'offre ca !
                                                </a>
                                            </div>
                                        <?php elseif ($object['gifted_by'] === $_SESSION['user']['id']): ?>
                                            <div class="text-center">
                                                <a href="actions/objectNotGifted.php?id=<?php echo $object['id']; ?>&friendId=<?php echo $currentUser['code']; ?>" class="btn btn-default btn-xs">
                                                    finalement j'offre plus ca ...
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    <?php endif; ?>

                                    <div class="text-right">
                                        <a href="#" class="btn btn-xs btn-default" data-more-info data-toggle="popover" data-placement="top" data-content="<?php echo $object['description']; ?>">
                                            + d'infos
                                        </a>
                                    </div>
                                </div>

                                <?php if ('' !== $object['link']): ?>
                                    <div class="prix">
                                        <a target="_blank" href="<?php echo $object['link']; ?>" class="btn btn-lg btn-default btn-block">
                                            <span class="hidden-xs">C'est trop bien ! </span> Où j'trouve ca ?
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <?php if (isset($_SESSION['user']) && $_SESSION['user']['id'] === $currentUser['id']): ?>
                        <div class="col-xs-12 col-sm-6 col-md-4 grid-item">
                            <a class="panel panel-default panel-object btn-add" href="#" data-toggle="modal" data-target="#myModal">
                                <i class="fa fa-gift"></i>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!isset($_SESSION['user'])): ?>
            <div class="row">
                <div class="col-xs-12 col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
                    <a class="btn btn-primary btn-block btn-lg" href="#" data-toggle="modal" data-target="#addUserModal" style="margin-top: 40px; margin-bottom: 40px;">
                        <i class="fa fa-plus"></i> Créer ma liste
                    </a>
                </div>
            </div>
        <?php endif; ?>

        <div class="fb-like hidden-xs" data-href="http://datcharrye.free.fr/listeNoel/" data-layout="standard" data-action="like" data-size="small" data-show-faces="true" data-share="true"></div>

        <div class="text-center">
            <img src="img/<?php echo $currentTheme['label']; ?>/pin.png" width="300px" style="margin-bottom: 100px; margin-top: 50px;">
        </div>
    </div>

    <div class="right-panel">
        <a href="#" data-toggle-comment class="comment">
            <i class="fa fa-comments-o"></i>
            <i class="fa fa-times"></i>
            <span class="fb-comments-count" data-href="http://datcharrye.free.fr/listeNoel/index.php?user=<?php echo $currentUser['code']; ?>"></span>
        </a>

        <div class="visible-xs text-right">
            <a href="#" data-toggle-comment><i class="fa fa-times"></i></a>
        </div>

        <div class="fb-comments" data-href="http://datcharrye.free.fr/listeNoel/index.php?user=<?php echo $currentUser['code']; ?>" data-width="100%" data-numposts="100" data-order-by="social"></div>
    </div>
</div>
