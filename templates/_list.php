<div class="row">
    <div class="grid">
        <?php foreach ($objects as $object) : ?>
            <div class="col-xs-12 col-sm-6 col-md-4 grid-item">
                <div class="
                    panel panel-default panel-object
                    <?php if (isset($_SESSION['user']) && $_SESSION['user']['id'] !== $currentUser['id'] && null !== $object['gifted_by'] && $object['gifted_by'] !== $_SESSION['user']['id']) : ?>
                        gifted
                    <?php endif; ?>
                ">
                    <?php if (isset($_SESSION['user']) && $_SESSION['user']['id'] === $currentUser['id']) : ?>
                        <a href="#" class="delete-obj edit-obj" data-toggle="modal" data-target="#modal-edit-object-<?php echo $object['id']; ?>" data-toggle2="tooltip" data-original-title="ajouter une envie">
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
                    <?php endif; ?>

                    <div class="panel-detail">
                        <p>
                            <?php echo nl2br(substr($object['description'], 0, 300)); ?>

                            <?php if (300 < strlen($object['description'])) : ?>
                                [...]
                            <?php endif; ?>
                        </p>

                        <div class="text-center marged-top">
                            <?php if (isset($_SESSION['user']) && $_SESSION['user']['id'] !== $currentUser['id']) : ?>
                                <?php if (null === $object['gifted_by']) : ?>
                                    <a href="actions/objectGifted.php?id=<?php echo $object['id']; ?>&friendId=<?php echo $currentUser['code']; ?>" class="btn btn-primary">
                                        <i class="fa fa-gift"></i> J'offre ca !
                                    </a>
                                <?php elseif ($object['gifted_by'] === $_SESSION['user']['id']) : ?>
                                    <a href="actions/objectNotGifted.php?id=<?php echo $object['id']; ?>&friendId=<?php echo $currentUser['code']; ?>" class="btn btn-default">
                                        finalement j'offre plus ca ...
                                    </a>
                                <?php endif; ?>
                            <?php endif; ?>

                            <a href="#" class="btn btn-default" data-toggle="modal" data-target="#object-<?php echo $object['id']; ?>">
                                + d'infos
                            </a>
                        </div>

                        <div class="info-comment">
                            <i class="fa fa-comments-o"></i> <span class="fb-comments-count" data-href="http://datcharrye.free.fr/listeNoel/index.php?object=<?php echo $object['id']; ?>"></span>
                        </div>
                    </div>

                    <?php if ('' !== $object['link']) : ?>
                        <div class="prix">
                            <a target="_blank" href="<?php echo $object['link']; ?>" class="btn btn-lg btn-default btn-block">
                                <span class="hidden-xs">C'est trop bien ! </span> Où je trouve ca ?
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>

        <?php if (isset($_SESSION['user']) && $_SESSION['user']['id'] === $currentUser['id']) : ?>
            <div class="col-xs-12 col-sm-6 col-md-4 grid-item">
                <a class="panel panel-default panel-object btn-add" href="#" data-toggle="modal" data-target="#myModal" data-toggle2="tooltip" data-original-title="ajouter une envie">
                    <i class="fa fa-gift"></i>
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php foreach ($objects as $object) : ?>
    <div class="modal modal-object fade" id="object-<?php echo $object['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="modalObject<?php echo $object['id']; ?>">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title" id="myModalLabel"><?php echo $object['nom']; ?></h3>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <?php if ('' !== $object['image_url']) : ?>
                            <div class="col-xs-12 text-center" style="margin-bottom: 15px;">
                                <img src="<?php echo $object['image_url']; ?>" style="width: 100%;">
                            </div>
                        <?php endif; ?>

                        <div class="col-xs-12">
                            <p>
                                <?php echo nl2br($object['description']); ?>
                            </p>
                        </div>

                        <div class="col-xs-12">
                            <div class="fb-comments fb-comments-object" data-href="http://datcharrye.free.fr/listeNoel/index.php?object=<?php echo $object['id']; ?>" data-width="100%" data-numposts="100" data-order-by="reverse_time"></div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <div class="text-center">
                        <?php if ('' !== $object['link']) : ?>
                            <a target="_blank" href="<?php echo $object['link']; ?>" class="btn btn-default">
                                C'est trop bien ! Où je trouve ca ?
                            </a>
                        <?php endif; ?>

                        <?php if (isset($_SESSION['user']) && $_SESSION['user']['id'] !== $currentUser['id']) : ?>
                            <?php if (null === $object['gifted_by']) : ?>
                                <a href="actions/objectGifted.php?id=<?php echo $object['id']; ?>&friendId=<?php echo $currentUser['code']; ?>" class="btn btn-primary">
                                    <i class="fa fa-gift"></i> J'offre ca !
                                </a>
                            <?php elseif ($object['gifted_by'] === $_SESSION['user']['id']) : ?>
                                <a href="actions/objectNotGifted.php?id=<?php echo $object['id']; ?>&friendId=<?php echo $currentUser['code']; ?>" class="btn btn-default btn-xs">
                                    finalement j'offre plus ca ...
                                </a>
                            <?php endif; ?>
                        <?php endif; ?>

                        <?php if (isset($_SESSION['user']) && $_SESSION['user']['id'] === $currentUser['id']) : ?>
                            <a href="#" class="delete-obj edit-obj btn btn-primary" data-dismiss="modal" data-toggle="modal" data-target="#modal-edit-object-<?php echo $object['id']; ?>" data-toggle2="tooltip" data-original-title="ajouter une envie">
                                <i class="fa fa-pencil"></i> Modifier
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>
