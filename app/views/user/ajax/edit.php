<?php
/**
 * Created by PhpStorm.
 * User: LEOBA / Willing
 * Date: 14/11/2018
 * Time: 18:31
 */
?>

<div class="errorEdit"></div>
<form action="" method="post" id="formEditUser">
    <input type="hidden" name="hiddenIdUserEdit" id="hiddenIdUserEdit" value="<?php echo $user->useid; ?>">
    <div class="form-group">
        <label for="nomEditUser">Nom d'utilisateur</label>
        <input type="text" name="nomEditUser" id="nomEditUser" value="<?php echo $user->uselogin ?>" class="form-control" placeholder="nom du user" required>
        <span class="help-block"></span>
    </div>
    <div class="form-group">
        <label for="oldmdpEditUser">Ancien mot de passe</label>
        <input type="password" name="oldmdpEditUser" id="oldmdpEditUser" class="form-control" placeholder="votre ancien mot de passe" required>
        <span class="help-block"></span>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="mdpEditUser">Nouveau mot de passe</label>
                <input type="password" name="mdpEditUser" id="mdpEditUser" class="form-control" placeholder="nouveau mot de passe">
                <span class="help-block"></span>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="mdp2EditUser">Confirmation du mot de passe</label>
                <input type="password" name="mdp2EditUser" id="mdp2EditUser" class="form-control" placeholder="confirmer le mot de passe">
                <span class="help-block"></span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-5">
            <div class="form-group">
                <label for="profileEditUser">Profile</label>
                <select name="profileEditUser" id="profileEditUser" class="form-control">
                    <option value="">Profile</option>
                    <?php foreach ($profiles as $profile) {
                        ?>
                        <option value="<?php echo $profile->prfid ?>" <?php echo ($user->useprofile == $profile->prfid) ? 'selected' : '' ?>><?php echo ucfirst($profile->prflibelle); ?></option>
                        <?php
                    }
                    ?>
                </select>
                <span class="help-block"></span>
            </div>
        </div>
        <div class="col-md-7">
            <div class="form-group">
                <label for="employerEditUser">Employer</label>
                <select name="employerEditUser" id="employerEditUser" class="form-control">
                    <option value="">Liste des emplyer</option>
                    <?php foreach ($employes as $employe) {
                        ?>
                        <option value="<?php echo $employe->empid; ?>" <?php echo ($user->useemploye == $employe->empid) ? 'selected' : '' ?>><?php echo ucfirst($employe->empnom) . ' ' . ucfirst($employe->empprenom) ?></option>
                        <?php
                    }
                    ?>
                </select>
                <span class="help-block"></span>
            </div>
        </div>
    </div>

    <div class="clear">
        <div class="btn-group right">
            <button type="submit" class="btn btn-primary right" id="btnSubFormEdit">Modifier</button>
        </div>
    </div>
</form>

<script type="text/javascript">
    $(document).ready(function () {
        $('#formEditUser').submit(function () {
            var formValide = true;

            if ($('#nomEditUser').val() == '') {
                fieldForm($('#nomEditUser'), 'Veillez entrer votre nom d\'utilisateur', 1);
                formValide = false;
            }else {
                fieldForm($('#nomEditUser'), '', 0);
            }

            if ($('#profileEditUser').val() == '') {
                fieldForm($('#profileEditUser'), 'Veillez choisir votre profile', 1);
                formValide = false;
            }else {
                fieldForm($('#profileEditUser'), '', 0);
            }

            if ($('#employerEditUser').val() == '') {
                fieldForm($('#employerEditUser'), 'Veillez chosir un employe', 1);
                formValide = false;
            }else {
                fieldForm($('#employerEditUser'), '', 0);
            }

            if ($('#oldmdpEditUser').val() == '') {
                fieldForm($('#oldmdpEditUser'), 'Veillez saisir votre ancien mot de passe', 1);
                formValide = false;
            }else {
                $.ajax({
                    url: 'user/equalMdp',
                    type: 'post',
                    dataType: 'json',
                    data: {
                        idUser: $('#hiddenIdUserEdit').val(),
                        pwd: $('#oldmdpEditUser').val()
                    },
                    success: function (data) {
                        if (data.error == 0) {
                            fieldForm($('#oldmdpEditUser'), '', 0);
                        }else {
                            formValide = false;
                            fieldForm($('#oldmdpEditUser'), 'Mot de passe incorrect', 1);
                        }
                    },
                    error: function () {
                        alert('Une erreur s\'est survnue lors du traitement');
                    }
                });
                // fieldForm($('#oldmdpEditUser'), '', 0);
            }

            if ($('#mdp2EditUser') != '' && $('#mdpEditUser') != '' && ($('#mdp2EditUser').val() != $('#mdpEditUser').val())) {
                fieldForm($('#mdp2EditUser'), 'Vos deux mot de passes ne sont pas identiques', 2);
                fieldForm($('#mdpEditUser'), '', 2);
                formValide = false;
            }else {
                if ($('#mdpEditUser').val() == '') {
                    fieldForm($('#mdpEditUser'), 'Veillez entrer votre mot de passe', 1);
                    formValide = false;
                }else {
                    fieldForm($('#mdpEditUser'), '', 0);
                }

                if ($('#mdp2EditUser').val() == '') {
                    fieldForm($('#mdp2EditUser'), 'Veillez confirmer votre mot de passe', 1);
                    formValide = false;
                }else {
                    fieldForm($('#mdp2EditUser'), '', 0);
                }
            }

            if (formValide) {
                $.ajax({
                    url: 'user/edit',
                    type: 'post',
                    dataType: 'json',
                    data: $(this).serialize(),
                    success: function (data) {
                        if (data.error == 0) {
                            $('#editUser .modal-body .container-form-EditUser').html('<p>Un nouvelle utilisateur ajouté</p>');
                            $('#editUser .modal-dialog').append(data.modalFooter);
                            $('#editUser .modal-dialog').addClass('small-modal');
                            // $('#editUser')
                        }else if (data.error == 1) {
                            $('#editUser .errorEdit').html(data.mssge);
                        }else {
                            $('#editUser .modal-header h4').html('Echec');
                            $('#editUser .modal-body').html('<p>Erreur de serveur. L\'utilisateur n\'a pas été ajouté. <br>Veillez réessayer plutard</p>');
                            $('#editUser .modal-content').append(data.modalFooter);
                            $('#editUser .modal-dialog').addClass('small-modal');
                        }
                    },
                    error: function (xhr) {
                        alert('Une erreur s\'est survnue lors du traitement');
                    }
                })
            }


            return formValide;
        });

        $('#oldmdpEditUser').blur(function (e) {
            if ($(this).val() != '') {
                $.ajax({
                    url: 'user/equalMdp',
                    type: 'post',
                    dataType: 'json',
                    data: {
                        idUser: $('#hiddenIdUserEdit').val(),
                        pwd: $('#oldmdpEditUser').val()
                    },
                    success: function (data) {
                        if (data.error == 0) {
                            fieldForm($('#oldmdpEditUser'), '', 0);
                        }else {
                            fieldForm($('#oldmdpEditUser'), 'Mot de passe incorrect', 1);
                        }
                    },
                    error: function () {
                        alert('Une erreur s\'est survnue lors du traitement');
                    }
                });
            }
        });

        $('#mdpEditUser').blur(function () {
            if ($(this).val() != '' && $('#mdp2EditUser').val() != '') {
                if ($(this).val() != $('#mdp2EditUser').val()) {
                    fieldForm($('#mdp2EditUser'), 'Vos deux mot de passes ne sont pas identiques', 2);
                    fieldForm($('#mdpEditUser'), '', 2);
                }
            }
        });

        $('#mdp2EditUser').blur(function () {
            if ($(this).val() != '' && $('#mdpEditUser').val() != '') {
                if ($(this).val() != $('#mdpEditUser').val()) {
                    fieldForm($('#mdp2EditUser'), 'Vos deux mot de passes ne sont pas identiques', 2);
                    fieldForm($('#mdpEditUser'), '', 2);
                }
            }
        });
    });
</script>
