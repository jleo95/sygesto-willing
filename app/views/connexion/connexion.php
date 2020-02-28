<?php
/**
 * Created by PhpStorm.
 * User: LEOBA
 * Date: 07/07/2018
 * Time: 00:26
 */
?>

<!doctype html>
<html lang="fr">
    <head>
        <?php
        echo $base_href;
        ?>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" href="assets/fonts/css/fontawesome-all.css">
        <link rel="stylesheet" href="../../../assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="../../../assets/css/connexion.css">
        <title>Connection</title>
    </head>
    <body>

        <div class="container-fluid" style="margin-top: 125px;">
            <div class="img" style="margin-left:43%">
                    <img src="../../../assets/img/unnamed.jpg" alt="login">
                </div>
            <div align="center" style="margin:auto; width: 20%">
                <div id="submenu" style="text-shadow:1px 1px 2px #808040; font-variant:small-caps;font-weight:bold;border-top-style:solid;">
                    Portail De Connexion.
                </div>
            </div>
            <div class="container-fluid connexion">

                <div class="img">
                    <img src="../../../assets/img/login.jpeg" alt="login">
                </div>

                <div class="form">
                        <?php
                        if (isset($error) AND $error) {
                            ?>
                            <div class="alert alert-warning">
                                <strong>Attention !</strong>
                                <p>Votre login ou mot de passe est incorrect</p>
                            </div>
                            <div class="btn-submit-danger">
                                <a href="connexion" class="btn btn"><i class="fa fa-recycle"></i> Ressayer</a>
                            </div>
                            <?php
                        }else {
                            ?>
                    <form action="" method="post" id="formLoginUser">
                            <div class="form-group">
                                <input type="text" name="username" id="username" class="form-control" placeholder="nom d'utilisateur">
                            </div>
                            <div class="form-group">
                                <input type="password" name="pwd" id="pwd" class="form-control" placeholder="mot de passe">
                            </div>

                            <div class="btn-submit">

                                <button class="btn disabled" id="btnLogin" disabled><i class="fa fa-chevron-right"></i> Se connecter</button>
                            </div>
                    </form>
                        <?php
                        }
                        ?>
                </div>

            </div>
        </div>

    <script type="text/javascript" src="../../../assets/js/jquery.min.js"></script>
    <script type="text/javascript" src="../../../assets/js/bootstrap.js"></script>
        <script type="text/javascript">
            $(document).ready(function (e) {
                $('#username').keyup(function () {
                    if ($('#pwd').val() != '') {
                        console.log('cool');
                        $('#btnLogin').removeAttr('disabled');
                        $('#btnLogin').removeClass('disabled');
                    }
                });
                $('#pwd').keyup(function () {
                    if ($('#username').val() != '') {
                        console.log('cool');
                        $('#btnLogin').removeAttr('disabled');
                        $('#btnLogin').removeClass('disabled');
                    }
                });

                $('#formLoginUser').submit(function (e) {
                    var formValide = true;
                    if ($('#username').val() == '') {
                        formValide = false;
                    }

                    if ($('#pwd').val() == '') {
                        formValide = false;
                    }

                    return formValide;
                });
            })
        </script>

    </body>
</html>
