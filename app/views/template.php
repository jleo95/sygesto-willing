<?php require APP . DS . 'views' . DS . 'header.php'?>
            <div class="left-container">
                <div class="container-menu menus scrollbar-deep-purple">
                    <?php echo $menus; ?>
                </div>
            </div>
            <div class="center-container">
                <div class="views scrollbar-deep-purple">
                    <div class="title-view">
                        <h2> <i class="fa fa-arrow-right"></i> <?php echo ucfirst($title_view); ?></h2>
                    </div>
                    <?php echo $content_for_views; ?>
                </div>

            </div>
            <div id="loading">
                <div class="loader">
                    <p>En cours</p>
                    <img alt="" src="assets/img/ajax-loader.gif">

                </div>
            </div>
<?php require APP . DS . 'views' . DS . 'footer.php';

