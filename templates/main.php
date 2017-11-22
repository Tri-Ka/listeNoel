<?php include '_connect.php'; ?>
<?php include '_friendList.php'; ?>

<div class="container">
    <div class="bs-docs-section clearfix">
        <?php include '_top.php'; ?>
        <?php include '_share.php'; ?>

        <?php if ($currentUser): ?>
            <?php include '_list.php'; ?>
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

        <?php include '_bottom.php'; ?>
    </div>

    <?php include '_rightPanel.php'; ?>
</div>
