<?php
/**
 * Created by PhpStorm.
 * User: LEOBA/Willing
 * Date: 28/10/2018
 * Time: 01:48
 */

?>

<!DOCTYPE html/>
<html>
<head>
    <?php
    echo $base_href;
    ?>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/fonts/font-awesome/css/font-awesome.min37cb.css">
    <link rel="stylesheet" href="assets/font-awesome/css/font-awesome.css">
    <link rel="stylesheet" href="assets/css/fresh-bootstrap-table.css">
    <link rel="stylesheet" href="assets/css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="assets/css/styles.css">

    <!--    style for date -->
    <link rel="stylesheet" href="assets/css/classic.css">
    <link rel="stylesheet" href="assets/css/classic.date.css">

    <link rel="stylesheet" href="assets/js/gritter/css/jquery.gritter.css">

<!--    <link rel="stylesheet" href="assets/css/style.css">-->
    <?php echo $style ?>
    <!--        jquery script       -->
    <script type="text/javascript" src="assets/js/jquery-1.11.2.min.js"></script>
    <script src="assets/js/jquery.min.js"></script>

    <!--    bootstrap -->
    <script src="assets/js/bootstrap.min.js"></script>


    <!--        plugin table -->
    <script type="text/javascript" src="assets/js/bootstrap-table.js"></script>

    <!--    picker date -->
    <script src="assets/js/picker/picker.js"></script>
    <script src="assets/js/picker/picker.date.js"></script>
    <script src="assets/js/picker/picker.time.js"></script>
    <script src="assets/js/bootstrap-datepicker.js"></script>

    <!--    gitter-->
    <script type="text/javascript" src="assets/js/gritter/js/jquery.gritter.js"></script>

    <script type="text/javascript" src="assets/js/scripts.js"></script>

<!--    <script type="text/javascript" src="assets/js/clients.js"></script>-->

    <title><?php echo ucfirst($title_page) ?></title>
</head>
<body>
    <div class="container-fluid main-container">
        <div class="header">
            <div class="clear">
                <div class="header-logo left">
                    <p> <i class="fa fa-cart-arrow-down "></i><span>Myoffshore</span></p>
                </div>
                <div class="header-profile right">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="date-container">
                                    <div class="clear">
                                        <p class="date">
                                            <span class="clock"><i class="fa fa-clock-o"></i> &nbsp;</span>
                                            <?php
                                             $month = ['Jan', 'Fév', 'Mars', 'Av', 'Mai', 'Juin', 'Juil', 'Aoute', 'Sep', 'Oct', 'Nov', 'Dec'];
                                            $d = date('d');
                                            $y = date('Y');
                                            $m = date('m');
                                            echo '<span class=\'text\'>' . $d . ' ' . $month[$m - 1] . ' ' . $y . '</span>';
                                            ?>
                                        </p>
                                    </div>

                                </div>
                                <div class="time-container">
                                    <p class="time"><span></span></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="myprofile-item clear">
                                    <div class="myprofile-item-user left">
                                        <ul class="myprofile-item-menu">
                                            <li>
                                                <a href="#"><i class="fa fa-user"></i>&nbsp; Admin</a>
                                                <ul>
                                                    <li><a href="#"><i class="fa fa-user"></i> Mon profile</a></li>
                                                    <li><a href="user/connexion"><i class="fa fa-sign-in"></i> Mes connexions</a></li>
                                                    <li><a href="connexion/deconnexion"><i class="fa fa-key"></i> Se deconnecter</a></li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="myprofile-item-notification left">
                                        <ul class="myprofile-item-menu">
                                            <li>
                                                <a href="#"><i class="fa fa-envelope"></i></a>
                                                <ul>
                                                    <li class="span"><a href="#">Aucune notification</a></li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>