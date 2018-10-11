<?php
    include 'config.php';
    include 'functions.php';
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title><?php echo $currentTheme['title']; ?> <?php if (isset($currentUser)) :
?> de <?php echo $currentUser['nom']; ?> <?php
               endif; ?></title>
        <link rel="icon" href="./favicon.ico" type="image/x-icon">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Ma liste de Noel">
        <?php if (isset($currentUser)) : ?>
            <meta property="og:title" content="La liste de Noël de <?php echo $currentUser['nom']; ?>" />
        <?php else : ?>
            <meta property="og:title" content="Ma liste de Noël" />
        <?php endif; ?>
        <meta property="og:image" content="http://datcharrye.free.fr/listeNoel/img/metaOg.jpg" />
        <meta property="fb:app_id" content="185139272403079" />
        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo auto_version('css/front/'.$currentTheme['label'].'/front.css'); ?>">
    </head>

    <body class="">
        <?php if (isset($_SESSION['error']) && null !== $_SESSION['error']) : ?>
            <div class="error">
                <a href="#" class="error-close close" data-error-close><span aria-hidden="true">&times;</span></a>
                <p><i class="fa fa-warning"></i> <?php echo $_SESSION['error']; ?></p>
            </div>

            <?php $_SESSION['error'] = null; ?>
        <?php endif; ?>

        <section class="main-section">
            <?php include 'templates/main.php'; ?>
        </section>

        <?php include 'templates/modalObject.php'; ?>
        <?php include 'templates/modalUserNew.php'; ?>
        <?php include 'templates/modalConnect.php'; ?>
        <?php include 'templates/modalUserEdit.php'; ?>

        <script src="js/jquery-1.11.3.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/masonry.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.imagesloaded/3.2.0/imagesloaded.pkgd.min.js"></script>
        <div id="fb-root"></div>

        <script src="js/script.js"></script>
        <script async src="https://static.addtoany.com/menu/page.js"></script>
        <script>(function(d, s, id) {
          var js, fjs = d.getElementsByTagName(s)[0];
          if (d.getElementById(id)) return;
          js = d.createElement(s); js.id = id;
          js.src = "//connect.facebook.net/fr_FR/sdk.js#xfbml=1&version=v2.8&appId=185139272403079";
          fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));</script>
    </body>
</html>
