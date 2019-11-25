<?php if (isset($_SESSION['user']) && $_SESSION['user']['id'] !== $currentUser['id']) : ?>
    <div class="box-check-display">
        <label for="check-gifted">Voir les Kdos offerts / offrir un Kdo</label>
        <input data-switch-gifted name="check-gifted" class="apple-switch" type="checkbox">
    </div>
<?php endif; ?>

<div class="row">
    <div class="grid">
        <?php if (isset($_SESSION['user']) && $_SESSION['user']['id'] === $currentUser['id']) : ?>
            <div class="col-xs-12 col-sm-6 col-md-4 grid-item">
                <a class="panel panel-default panel-object btn-add" href="#" data-toggle="modal" data-target="#myModal" data-toggle2="tooltip" data-original-title="ajouter une idée Kdo">
                    <i class="fa fa-gift"></i>
                </a>
            </div>
        <?php endif; ?>

        <?php foreach ($objects as $object) : ?>
            <div class="col-xs-12 col-sm-6 col-md-4 grid-item">
                <div id="idea-<?php echo $object['id']; ?>" class="
                    panel panel-default panel-object
                    <?php if (isset($_SESSION['user']) && $_SESSION['user']['id'] !== $currentUser['id'] && null !== $object['gifted_by']) : ?>
                        gifted
                    <?php endif; ?>
                ">
                    <?php if (isset($_SESSION['user']) && $_SESSION['user']['id'] === $currentUser['id']) : ?>
                        <a href="#" class="delete-obj edit-obj" data-toggle="modal" data-target="#modal-edit-object-<?php echo $object['id']; ?>" data-toggle2="tooltip" data-original-title="Modifier">
                            <i class="fa fa-pencil"></i>
                        </a>
                    <?php endif; ?>

                    <div class="nom">
                        <?php echo $object['nom']; ?>
                    </div>

                    <?php if ('' !== $object['image_url']) : ?>
                        <a href="#" data-toggle="modal" data-target="#object-<?php echo $object['id']; ?>" class="img-container text-center">
                            <img src="<?php echo $object['image_url']; ?>">
                        </a>
                    <?php else : ?>
                        <a href="#" data-toggle="modal" data-target="#object-<?php echo $object['id']; ?>" class="img-container text-center">
                            <img src="img/idea-default.jpg" style="width: 100%;">
                        </a>
                    <?php endif; ?>

                    <?php if (null !== $object['description'] && '' !== trim($object['description'])) : ?>
                        <div class="panel-detail">
                            <p>
                                <?php echo nl2br(substr($object['description'], 0, 300)); ?>

                                <?php if (300 < strlen($object['description'])) : ?>
                                    [...]
                                <?php endif; ?>
                            </p>
                        </div>
                    <?php endif; ?>

                    <div class="panel-bottom carded">
                        <a href="#" data-toggle="modal" data-target="#object-<?php echo $object['id']; ?>" class="icon-bottom" data-toggle2="tooltip" data-original-title="Commentaires">
                            <i class="fa fa-comments-o"></i>

                            <span class="sub-icon comments-count" data-comments-count-<?php echo $object['id']; ?>><?php echo count($object['comments']); ?></span>
                        </a>

                        <a href="#" data-show-modal class="icon-bottom" data-toggle="modal" data-target="#object-<?php echo $object['id']; ?>" data-toggle2="tooltip" data-original-title="Plus d'infos">
                            <i class="fa fa-info-circle"></i>
                        </a>

                        <?php if ('' !== $object['link']) : ?>
                            <a target="_blank" href="<?php echo $object['link']; ?>" class="icon-bottom"  data-toggle2="tooltip" data-original-title="Accéder au site">
                                <i class="fa fa-shopping-cart"></i>
                            </a>
                        <?php endif; ?>

                        <?php if (isset($_SESSION['user']) && $_SESSION['user']['id'] !== $currentUser['id']) : ?>
                            <?php if (null === $object['gifted_by']) : ?>
                                <a href="actions/objectGifted.php?id=<?php echo $object['id']; ?>&friendId=<?php echo $currentUser['code']; ?>" class="icon-bottom primary gifted-display gift-btn" data-toggle="tooltip" data-original-title="Offrir ce Kdo">
                                    <i class="fa fa-gift"></i>
                                </a>
                            <?php elseif ($object['gifted_by'] === $_SESSION['user']['id']) : ?>
                                <a href="actions/objectNotGifted.php?id=<?php echo $object['id']; ?>&friendId=<?php echo $currentUser['code']; ?>" class="icon-bottom gifted-display green gift-btn" data-toggle="tooltip" data-original-title="Ne plus offrir ce Kdo">
                                    <i class="fa fa-check"></i>

                                    <span class="sub-icon">
                                        <i class="fa fa-times"></i>
                                    </span>
                                </a>
                            <?php else : ?>
                                <a href="index.php?user=<?php echo $object['gifted_by_datas']['code']; ?>"
                                    data-toggle="tooltip" 
                                    data-original-title="Offert par: <?php echo $object['gifted_by_datas']['nom']; ?>" 
                                    class="gifted-by-infos__avatar icon-bottom gifted-display" 
                                    style="background-image: url('uploads/<?php echo $object['gifted_by_datas']['id'] .'/'. $object['gifted_by_datas']['pictureFile']; ?>')">
                                    <i class="fa"></i>
                                </a>
                            <?php endif; ?>
                        <?php endif; ?>
                        
                        <div data-reaction-list class="reaction-list">
                            <?php foreach($object['reactions'] as $k => $reaction): ?>
                                <div class="reaction">
                                    <img src="img/reaction/<?php echo $k; ?>.png" alt="">
                                    <span><?php echo count($object['reactions'][$k]); ?></span>
                                </div>
                            <?php endforeach; ?>

                            <?php if (isset($_SESSION['user'])): ?>
                                <?php if (0 === count($object['reactions'])): ?>
                                    <div class="reaction react-grey">
                                        <img src="img/reaction/3.png" alt="">
                                    </div>
                                <?php endif; ?>

                                <div class="reaction-details" style="">
                                    <ul class="reaction-choices">
                                        <li>
                                            <a href="actions/addReaction.php?object=<?php echo $object['id']; ?>&value=3">
                                                <img src="img/reaction/3.png" alt="">
                                            </a>
                                        </li>

                                        <li>
                                            <a href="actions/addReaction.php?object=<?php echo $object['id']; ?>&value=1">
                                                <img src="img/reaction/1.png" alt="">
                                            </a>
                                        </li>

                                        <li>
                                            <a href="actions/addReaction.php?object=<?php echo $object['id']; ?>&value=4">
                                                <img src="img/reaction/4.png" alt="">
                                            </a>
                                        </li>

                                        <li>
                                            <a href="actions/addReaction.php?object=<?php echo $object['id']; ?>&value=2">
                                                <img src="img/reaction/2.png" alt="">
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php foreach ($objects as $object) : ?>
    <div class="modal modal-object fade" data-object-id="<?php echo $object['id']; ?>" id="object-<?php echo $object['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="modalObject<?php echo $object['id']; ?>">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><i class="fa fa-times"></i></span></button>
                    <h3 class="modal-title" id="myModalLabel"><?php echo $object['nom']; ?></h3>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <?php if ('' !== $object['image_url']) : ?>
                            <div class="col-xs-12 text-center" style="margin-bottom: 15px;">
                                <img src="<?php echo $object['image_url']; ?>" style="width: 100%;">
                            </div>
                        <?php else : ?>
                            <div class="col-xs-12 text-center" style="margin-bottom: 15px;">
                                <img src="img/idea-default.jpg" style="width: 100%;">
                            </div>
                        <?php endif; ?>

                        <?php if (null !== $object['description'] && '' !== trim($object['description'])) : ?>
                            <div class="col-xs-12">
                                <p>
                                    <?php echo nl2br($object['description']); ?>
                                </p>
                            </div>
                        <?php endif; ?>

                        <div class="col-xs-12">
                            <?php foreach($object['reactions'] as $k => $reaction): ?>
                                <div class="reaction">
                                    <div class="react-base" style="background-image: url(img/reaction/<?php echo $k; ?>.png)"></div>
                                    
                                    <?php foreach($reaction as $react): ?>
                                        <div class="react-avatar" data-toggle="tooltip" data-original-title="<?php echo $react['user']['nom']; ?>" style="background-image: url('uploads/<?php echo $react['user']['id'] .'/'. $react['user']['pictureFile']; ?>')"></div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="col-xs-12">
                            <div class="comments" data-comments>
                                <h5>commentaires:</h5>
                                <ul class="comments-list" data-comments-list>
                                    <?php foreach ($object['comments'] as $comment) : ?>
                                        <li class="comment" data-comment>
                                            <div class="comment-avatar" style="background-image: url('uploads/<?php echo $comment['user']['id'] .'/'. $comment['user']['pictureFile']; ?>')"></div>

                                            <div class="comment-content">
                                                <span class="comment-user"><?php echo $comment['user']['nom']; ?></span> <?php echo nl2br($comment['content']); ?>

                                                <?php if (isset($_SESSION['user']) &&  $comment['user']['id'] === $_SESSION['user']['id']) : ?>
                                                    <a href="actions/deleteComment.php?id=<?php echo $comment['id']; ?>" class="delete-comment" data-delete-comment data-toggle="tooltip" data-original-title="supprimer mon commentaire">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                <?php endif ; ?>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>

                                <?php if (isset($_SESSION['user'])) : ?>
                                    <form data-form-comment action="actions/addComment.php" method="post" enctype="multipart/form-data">
                                        <input type="hidden" data-object-id name="productId" value="<?php echo $object['id']; ?>">

                                        <div class="form-comment-content">
                                            <div class="comment-avatar" style="background-image: url('uploads/<?php echo $_SESSION['user']['id'] .'/'. $_SESSION['user']['pictureFile']; ?>')"></div>
                                            <input data-submit-comment type="submit" name="submit" value="ok" class="btn btn-primary">
                                            <textarea rows="1" class="comment-form-content" name="content" required="required"></textarea>
                                        </div>
                                    </form>
                                <?php else : ?>
                                    <div class="help-block text-center">
                                        <p>connectez vous pour commenter cette idée KDO !</p>

                                        <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#connectModal">
                                            <i class="fa fa-power-off"></i> Se connecter
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <?php if (isset($_SESSION['user']) && $_SESSION['user']['id'] !== $currentUser['id']) : ?>
                        <div class="modal-footer__gift">
                            <?php if (null === $object['gifted_by']) : ?>
                                <a href="actions/objectGifted.php?id=<?php echo $object['id']; ?>&friendId=<?php echo $currentUser['code']; ?>" class="icon-bottom primary gifted-display gift-btn" data-toggle="tooltip" data-original-title="Offrir ce Kdo">
                                    <i class="fa fa-gift"></i>
                                </a>
                            <?php elseif ($object['gifted_by'] === $_SESSION['user']['id']) : ?>
                                <a href="actions/objectNotGifted.php?id=<?php echo $object['id']; ?>&friendId=<?php echo $currentUser['code']; ?>" class="icon-bottom green gifted-display gift-btn" data-toggle="tooltip" data-original-title="Ne plus offrir ce Kdo">
                                    <i class="fa fa-check"></i>

                                    <span class="sub-icon">
                                        <i class="fa fa-times"></i>
                                    </span>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <?php if ('' !== $object['link']) : ?>
                        <div class="modal-footer__link text-center">
                            <a target="_blank" href="<?php echo $object['link']; ?>" class="btn btn-primary btn-buy"  data-toggle2="tooltip" data-original-title="Voir ce KDO">
                                Voir ce KDO
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>
