<?php require APP . DS . 'views' . DS . 'header.php'?>

    <div class="center-container index-page">
        <div class="views scrollbar-deep-purple container">
            <div class="row">
                <div class="col-md-3 col-sm-4">
                    <div style="border: 1px solid red; width: 100%; height: 105px;"></div>
                </div>
                <div class="col-md-9 col-sm-8">
                    <div class="title-view">
                        <h2> <i class="fa fa-arrow-right"></i> <?php echo ucfirst($title_view); ?></h2>
                    </div>

                    <div class="row">
                        <?php foreach ($menus as $menu) {
                            ?>
                        <div class="col-md-3 col-sm-6">
                            <a href="<?php echo $menu->url; ?>" class="index-menuItem">
<!--                                <div class="col-md-3 index-menuHover"></div>-->
                                <div class="card">
                                    <div class="card-title"><span><?php echo $menu->grplibelle?></span></div>
                                    <div class="card-body">
                                        <span style="font-size: 27px"><?php echo $menu->grpicone;?></span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div id="loading">
        <div class="loader">
            <p>En cours</p>
            <img alt="" src="assets/img/ajax-loader.gif">

        </div>
    </div>

<?php require APP . DS . 'views' . DS . 'footer.php';