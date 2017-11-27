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
                            <?php echo nl2br(substr($object['description'], 0, 300)); ?>

                            <?php if (300 < strlen($object['description'])): ?>
                                [...]
                            <?php endif; ?>
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
                                <span class="hidden-xs">C'est trop bien ! </span> Où je trouve ca ?
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>

        <?php if (isset($_SESSION['user']) && $_SESSION['user']['id'] === $currentUser['id']): ?>
            <div class="col-xs-12 col-sm-6 col-md-4 grid-item">
                <a class="panel panel-default panel-object btn-add" href="#" data-toggle="modal" data-target="#myModal" data-toggle2="tooltip" data-original-title="ajouter une envie">
                    <i class="fa fa-gift"></i>
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>
